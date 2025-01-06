<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use DB;

class AdminController extends Controller
{
    public function index() {
        $userId = session('user_id');
        $user = User::findOrFail($userId);

        $totalMoney = DB::table('tbl_payments')->sum('amount');

        $totalUsers = User::count();

        $totalCourses = DB::table('tbl_user_progress')
            ->distinct('users_id', 'courses_id') 
            ->count('courses_id');

        $dailyMoney = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date'); 
        $registeredUsers = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role'); 

        $studentsCount = User::where('role', 'student')->count();
        $teachersCount = User::where('role', 'teacher')->count();
        $adminsCount = User::where('role', 'admin')->count();

        $logFile = storage_path('logs/laravel.log');

        $accessCount = 0;
        if (file_exists($logFile)) {
            $logContents = file($logFile);
            foreach ($logContents as $line) {
                if (str_contains($line, 'Học viên truy cập giao diện ngoài')) {
                    $accessCount++;
                }
            }
        }
        return view('Admin.home', compact('user', 'totalMoney', 'totalUsers', 'totalCourses', 'accessCount', 'dailyMoney', 'registeredUsers', 'studentsCount', 'teachersCount', 'adminsCount'));
    }

}
