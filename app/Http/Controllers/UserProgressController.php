<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use Illuminate\Http\Request;

class UserProgressController extends Controller
{
    public function markLectureAsCompleted($lectureId) {
        $userId = auth()->id();

        $progress = UserProgress::where('users_id', $userId)
                                ->where('lectures_id', $lectureId)
                                ->first();

        if ($progress) {
            $progress->update([
                'status' => 'Completed',
                'progress' => 100,
            ]);
        }

        return $this->calculateCourseProgress($progress->courses_id);
    }

    public function markExerciseAsCompleted($exerciseId) {
        $userId = auth()->id();

        $progress = UserProgress::where('users_id', $userId)
                                ->where('exercises_id', $exerciseId)
                                ->first();

        if ($progress) {
            $progress->update([
                'status' => 'Completed',
                'progress' => 100,
            ]);
        }

        return $this->calculateCourseProgress($progress->courses_id);
    }

    public function calculateCourseProgress($courseId) {
        $userId = auth()->id();

        // Lấy tổng số bài giảng và bài tập trong khóa học
        $totalLectures = Lecture::whereHas('chapter.course', fn($q) => $q->where('id', $courseId))->count();
        $totalExercises = Exercise::whereHas('lecture.chapter.course', fn($q) => $q->where('id', $courseId))->count();
        $totalItems = $totalLectures + $totalExercises;

        // Lấy số lượng bài giảng và bài tập đã hoàn thành của người dùng
        $completedLectures = UserProgress::where('users_id', $userId)
                                         ->where('courses_id', $courseId)
                                         ->whereNotNull('lectures_id')
                                         ->where('status', 'Completed')
                                         ->count();

        $completedExercises = UserProgress::where('users_id', $userId)
                                          ->where('courses_id', $courseId)
                                          ->whereNotNull('exercises_id')
                                          ->where('status', 'Completed')
                                          ->count();

        $completedItems = $completedLectures + $completedExercises;

        // Tính phần trăm tiến độ
        $progressPercentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;

        return response()->json(['progress' => round($progressPercentage, 2)]);
    }

}
