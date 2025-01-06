<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập và có phải là admin không
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect('/admin/login'); // Chuyển hướng đến trang đăng nhập
        }

        return $next($request);
    }
}
