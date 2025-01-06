<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class WalletController extends Controller
{
    public function topUp(Request $request)
    {
        // Xác thực đầu vào
        $request->validate([
            'amount' => 'required|numeric|min:1000', // Số tiền tối thiểu là 1,000đ
        ]);

        // Lấy user ID từ session
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để nạp tiền.');
        }

        // Lấy thông tin user
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản của bạn.');
        }

        $amount = $request->input('amount');

        $amount = str_replace('.', '', $amount); 
        $amount = str_replace('đ', '', $amount); 

        // Chuyển số tiền thành số nguyên
        $amount = (int)$amount;
        if (($user->balance + $amount) > 99999999999999) {
            return redirect()->back()->with('error', 'Xin lỗi! Hệ thống học trực tuyến không nhận nổi lượng tiền vượt quá 14 số! Vui lòng sử dụng hết số dư hiện tại rồi nạp thêm!!!');
        }
        $user->balance += $amount;

        $user->save();

        // Cập nhật balance trong session
        Session::put('balance', $user->balance);

        // Trả về thông báo thành công
        return redirect()->back()->with('success', "Bạn đã nạp thành công " . number_format($amount, 0, ',', '.') . "đ vào ví.");
    }
}
