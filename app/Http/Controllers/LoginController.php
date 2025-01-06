<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Students;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use DB;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Kiểm tra thông tin đăng nhập
        $user = User::where('username', $request->username)
                    ->where('password', md5($request->password))
                    ->first();

        if ($user) {
            if ($user->status !== 'Active') {
                return redirect()->back()->with('error', 'Tài khoản của bạn chưa được kích hoạt hoặc đã bị vô hiệu hóa.');
            }
            // Assuming the student record is linked to the user
            $student = $user->student; // This uses the relationship defined earlier

            session([
                'user_id' => $user->id,
                'name' => $user->name,
                'balance' => $user->balance,
                'profile_image' => $student ? $student->profile_image : null, // Assuming profile_picture is a column in tbl_student
                'linked_id' => $user->linked_id,
            ]);
             if ($user->role === 'Admin') {
                return redirect()->route('admin.show', ['id' => $user->id]);
            }elseif ($user->role === 'Student') {
                session([
                    'user_id' => $user->id,
                    'name' => $student->name,
                    'public/images/profile_image' => $student->profile_image,
                    'student_id' => $student->id // Lưu student_id
                ]);

                Log::info('Học viên truy cập giao diện ngoài', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'time' => now(),
                ]);
                return redirect()->route('home', ['id' => $user->id]);
            }
        }

        if (!$user) {
             Session::put('message', 'Kiểm tra lại username hoặc password!!! Đăng ký tài khoản nếu chưa có!!!');
              return view('login');
        }
       
    }

    public function logout(Request $request)
    {
        // Xóa tất cả các session
        $request->session()->flush(); 

        return redirect()->route('login');
    }
    public function elearning(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('home');
    }

    public function show_register(){
        return view('register');
    }
    public function register(Request $request) {
         // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:tbl_user,username',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tạo bản ghi trong bảng tbl_students
        $student = Students::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'profile_image' => 'images.jfif',
            'status' => 'Active',
            'created_at' => now(),
        ]);

        // Tạo tài khoản trong bảng tbl_user với role là student
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => md5($request->password),
            'role' => 'Student',
            'linked_id' => $student->id,
        ]);

        return view('login');
    }

    public function promoteToTeacher($userId) {
    $user = User::find($userId);

    if ($user && $user->role === 'Student') {
        // Xóa bản ghi học viên trong tbl_students
        $student = Students::find($user->linked_id);
        $studentData = $student->toArray();
        $student->delete();

        // Tạo bản ghi giảng viên trong tbl_teacher
        $teacher = Teacher::create($studentData);

        // Cập nhật lại vai trò trong bảng tbl_user
        $user->update([
            'role' => 'Teacher',
            'linked_id' => $teacher->id,
        ]);
    }
}



}
