<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Course_UserController extends Controller
{
    public function registerCourse($courseId) {
    $user = auth()->user();
    $user->courses()->syncWithoutDetaching([$courseId]); // Chỉ đăng ký nếu chưa có
    }

    public function completeLecture($courseId, $lectureId) {
    $user = auth()->user();
    $progress = calculateProgress($courseId, $user->id); // Tính phần trăm tiến độ
    $user->courses()->updateExistingPivot($courseId, ['progress' => $progress]);
    }

    function calculateProgress($courseId, $userId) {
        $totalLectures = Lecture::where('course_id', $courseId)->count();
        $completedLectures = CompletedLecture::where('course_id', $courseId)
                           ->where('user_id', $userId)->count();
        return ($completedLectures / $totalLectures) * 100;
    }

}
