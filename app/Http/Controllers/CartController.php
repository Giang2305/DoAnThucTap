<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserProgress;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function save_cart(Request $request, $id)
    {
        $userId = Session::get('user_id'); // Lấy ID người dùng đang đăng nhập

        // Kiểm tra xem khóa học đã tồn tại trong giỏ hàng hay chưa
        $existingCart = Cart::where('user_id', $userId)->where('course_id', $id)->first();

        if (!$existingCart) {
            // Nếu chưa có, thêm mới
            Cart::create([
                'user_id' => $userId,
                'course_id' => $id,
            ]);
            // Tăng giá trị session "cart_count"
            $cartCount = Session::get('cart_count', 0);
            Session::put('cart_count', $cartCount + 1);
            return redirect()->back()->with('success', 'Khóa học đã được thêm vào giỏ hàng!');
        }

        // Nếu đã tồn tại, thông báo cho người dùng
        return redirect()->back()->with('info', 'Khóa học này đã có trong giỏ hàng!');
    }

     // Hiển thị giỏ hàng
    public function show_cart(){
        $userId = Session::get('user_id'); // Lấy ID người dùng đang đăng nhập
        $cartItems = Cart::with('course')->where('user_id', $userId)->get(); // Lấy danh sách giỏ hàng
        $totalPrice = $cartItems->sum(function ($item) {
            // Loại bỏ "đ" và chuyển đổi giá trị thành số
            return (int) str_replace(['đ', '.', ','], '', $item->course->Price);
        });

        return view('cart.show_cart', compact('cartItems', 'totalPrice'));
    }

    public function checkout(Request $request)
    {
        $userId = Session::get('user_id');

        // Lấy thông tin user
        $user = User::find($userId);

        // Lấy giỏ hàng
        $cartItems = Cart::where('user_id', $userId)->with('course.chapters.lectures.exercises')->get();

        $alreadyRegisteredCourses = []; // Danh sách khóa học đã đăng ký
        $payableCourses = []; // Danh sách khóa học chưa đăng ký
        $totalPrice = 0; // Tổng tiền phải thanh toán

        foreach ($cartItems as $item) {
            $course = $item->course;

            // Kiểm tra nếu khóa học đã được đăng ký
            $isRegistered = UserProgress::where('users_id', $userId)
                                         ->where('courses_id', $course->id)
                                         ->exists();

            if ($isRegistered) {
                // Thêm vào danh sách khóa học đã đăng ký
                $alreadyRegisteredCourses[] = $course->Name;
            } else {
                // Thêm vào danh sách khóa học chưa đăng ký
                $payableCourses[] = $item;
                $cleanPrice = (int) str_replace(['đ', '.', ','], '', $course->Price);
                $totalPrice += $cleanPrice;
            }
        }

        // Lấy tổng số khóa học trong giỏ hàng
        $cartCount = $cartItems->count();

        if ($user->balance >= $totalPrice) {
            // Trừ tiền trong tài khoản
            $user->balance -= $totalPrice;
            $user->save();

            foreach ($payableCourses as $item) {
                $course = $item->course;

                // Cập nhật tiến độ khóa học (nếu chưa tồn tại)
                foreach ($course->chapters as $chapter) {
                    foreach ($chapter->lectures as $lecture) {
                        UserProgress::create([
                            'users_id' => $userId,
                            'lectures_id' => $lecture->id,
                            'courses_id' => $course->id,
                            'progress' => 0,
                            'status' => 'Incomplete',
                        ]);

                        foreach ($lecture->exercises as $exercise) {
                            UserProgress::create([
                                'users_id' => $userId,
                                'lectures_id' => $lecture->id,
                                'exercises_id' => $exercise->id,
                                'courses_id' => $course->id,
                                'progress' => 0,
                                'status' => 'Incomplete',
                            ]);
                        }
                    }
                }

                // Cập nhật trạng thái thanh toán
                $cleanPrice = (int) str_replace(['đ', '.', ','], '', $course->Price);
                Payment::create([
                    'user_id' => $userId,
                    'amount' => $cleanPrice,
                    'status' => 'Completed',
                    'method' => 'Wallet',
                ]);

                // Xóa khóa học khỏi giỏ hàng
                $item->delete();
                $cartCount--; // Giảm số lượng khóa học trong giỏ hàng
            }

            // Cập nhật lại session
            Session::put('cart_count', $cartCount);
            $balance = Session::get('balance', 0);
            Session::put('balance', $balance - $totalPrice);

            // Nếu có khóa học đã đăng ký, thêm thông báo
            if (!empty($alreadyRegisteredCourses)) {
                return redirect()->back()->with([
                    'warning' => 'Bạn đã đăng ký các khóa học sau:',
                    'alreadyRegisteredCourses' => $alreadyRegisteredCourses,
                ]);
            }
            return redirect()->back()->with('success', 'Thanh toán thành công!');
        } else {
            // Thanh toán không thành công, cập nhật session và hiển thị modal
            Session::put('cart_count', $cartCount);
            return redirect()->back()->with([
                'error' => 'Số dư không đủ, vui lòng thanh toán qua ngân hàng hoặc Momo.',
                'showModal' => true,
            ]);
        }
    }

    // Xóa khóa học khỏi giỏ hàng
    public function remove_cart($id)
    {
        $userId = Session::get('user_id');
        Cart::where('user_id', $userId)->where('id', $id)->delete();

         // Giảm giá trị session "cart_count"
        $cartCount = Session::get('cart_count', 0);
        if ($cartCount > 0) {
            Session::put('cart_count', $cartCount - 1);
        }

        return redirect()->route('cart.show_cart')->with('success', 'Khóa học đã được xóa khỏi giỏ hàng!');
    }
}
