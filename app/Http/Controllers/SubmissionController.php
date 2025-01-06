<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\UserProgress;
use App\Models\User;
use App\Models\Exercises;
use DB;

class SubmissionController extends Controller
{
    public function submitExercise(Request $request, $exerciseId)
    {
        $userId = session('user_id');

        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!$userId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để nộp bài.');
        }

        // Kiểm tra hạn chót bài tập
        $exercise = Exercises::findOrFail($exerciseId);
        if ($exercise->due_date < now()) {
            return redirect()->back()->with('error', 'Bài tập đã hết hạn nộp.');
        }

        // Lấy file từ yêu cầu
        $file = $request->file('submission_file');

        // Kiểm tra nếu file hợp lệ
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('public/submissions', $fileName);

        // Kiểm tra nếu người dùng đã có bài nộp cho bài tập này
        $existingSubmission = DB::table('tbl_submissions')
                                ->where('users_id', $userId)
                                ->where('exercises_id', $exerciseId)
                                ->first();

        if ($existingSubmission) {
            // Nếu đã có bài nộp, chỉ cập nhật lại file_path
            DB::table('tbl_submissions')
                ->where('id', $existingSubmission->id)
                ->update([
                    'file_path' => basename($filePath), // Cập nhật lại file path
                    'status' => 'Incomplete', // Trạng thái vẫn là Incomplete
                    'updated_at' => now(),
                ]);
        } else {
            // Nếu chưa có bài nộp, tạo mới bản ghi
            DB::table('tbl_submissions')->insert([
                'users_id' => $userId,
                'exercises_id' => $exerciseId,
                'file_path' => basename($filePath),
                'status' => 'Incomplete',
                'created_at' => now(),
            ]);
        }

        // Kiểm tra nếu người dùng đã có thông tin tiến độ cho bài tập này
        $userProgress = UserProgress::where('users_id', $userId)
                                    ->where('exercises_id', $exerciseId)
                                    ->first();

        // Lấy thông tin bài giảng từ bài tập
        $lectureId = $exercise->lecture_id;

        if ($userProgress) {
            // Nếu đã có thông tin tiến độ, cập nhật trạng thái là "Completed"
            $userProgress->status = 'Completed';
            $userProgress->progress = 100; // Tiến độ 100% khi bài tập hoàn thành
            $userProgress->save();
        } else {
            // Nếu chưa có thông tin tiến độ, tạo mới một bản ghi với trạng thái "Completed"
            UserProgress::create([
                'users_id' => $userId,
                'exercises_id' => $exerciseId,
                'status' => 'Completed',
                'progress' => 100, // Tiến độ 100% khi bài tập hoàn thành
                'lectures_id' => $lectureId, // Thêm lecture_id vào bảng tbl_user_progress
                'courses_id' => $exercise->lecture->chapter->course->id, // Lấy ID khóa học của bài tập thông qua bài giảng
            ]);
        }

        // Hiển thị thông tin đã tạo mới thành công
        return redirect()->back()->with('message', 'Đã nộp bài thành công và cập nhật tiến độ.');
    }


    public function showSubmissions($exerciseId)
    {
        // Lấy thông tin bài tập
        $exercise = Exercises::findOrFail($exerciseId);

        // Lấy tất cả các bài nộp của học viên cho bài tập này
        $submissions = Submission::where('exercises_id', $exerciseId)->get();

        // Trả về view và truyền dữ liệu bài tập cùng danh sách các bài nộp
        return view('admin.exercises.score_exercise', compact('exercise', 'submissions'));
    }

    // Giáo viên chấm điểm bài nộp
    public function gradeSubmission(Request $request, $submissionId) 
    {
        // dd($request->all());
        // Lấy điểm từ yêu cầu và kiểm tra tính hợp lệ
        $score = $request->input('score');
        $feedback = $request->input('feedback');

        // Kiểm tra nếu điểm là một số và nằm trong phạm vi hợp lệ
        if (!is_numeric($score) || $score < 0 || $score > 10) {
            return redirect()->back()->with('error', 'Điểm phải là một số từ 0 đến 10.');
        }

        // Lấy thông tin bài nộp từ ID
        $submission = Submission::findOrFail($submissionId);

        // Kiểm tra trạng thái bài nộp, nếu đã chấm điểm rồi thì không được chấm lại
        if ($submission->status === 'Completed') {
            return redirect()->back()->with('error', 'Bài nộp đã được chấm điểm.');
        }

        // Cập nhật điểm và trạng thái
        $submission->score = $score;
        if ($score >= 9) {
            $submission->grading = 'Xuất sắc'; 
        }elseif ($score >= 8) {
            $submission->grading = 'Giỏi'; 
        }elseif ($score >= 6.5) {
            $submission->grading = 'Khá'; 
        }elseif ($score >= 5) {
            $submission->grading = 'Trung bình'; 
        }else {
            $submission->grading = 'Yếu'; 
        }
        $submission->feedback = $feedback;
        $submission->status = 'Completed';
        $submission->save(); // Lưu thay đổi vào database

        // Thông báo thành công
        return redirect()->back()->with('message', 'Đã chấm điểm thành công.');
    }

}
