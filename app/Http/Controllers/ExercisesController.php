<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Models\Exercises;
use App\Models\Submission;
use DB;

class ExercisesController extends Controller
{
//User View
    public function show($lectureId)
    {
        $userId = session('user_id');
        $lecture = Lecture::with('exercises')->findOrFail($lectureId);
        $chapter = $lecture->chapter;
        $course = $lecture->chapter->course;
        // Giả sử mỗi bài giảng có một bài tập liên kết với nó.
        // Nếu có nhiều bài tập, bạn có thể lấy danh sách bài tập tương ứng.
        $exercises = $lecture->exercises;
        $submission = Submission::where('users_id', $userId)
                            ->whereIn('exercises_id', $exercises->pluck('id')) // Lấy các bài nộp của user cho các bài tập trong bài giảng này
                            ->get();

        return view('exercises.ExerciseContent', compact('lecture', 'exercises', 'course', 'submission'));
    }
    public function completeExercise($exerciseId)
    {
        $userId = auth()->id();
        $lectureId = Exercise::find($exerciseId)->lecture_id;
        $courseId = Lecture::find($lectureId)->chapter->course_id;

        UserProgress::updateOrCreate(
            ['users_id' => $userId, 'lectures_id' => $lectureId, 'courses_id' => $courseId],
            ['progress' => 100, 'status' => 'Completed']
        );

        return response()->json(['message' => 'Bạn đã hoàn thành bài tập này']);
    }


//Admin View
    public function all_exercises(){
        $all_exercises = Exercises::with('lecture')->get(); // Lấy bài tập cùng thông tin bài giảng
        return view('admin.exercises.show_exercises', compact('all_exercises'));
    }

    //Create
    public function show_create_exercise()
    {
        $lectures = Lecture::all(); // Lấy danh sách bài giảng
        return view('admin.exercises.create_exercise', compact('lectures'));
    }

    public function create_exercise(Request $request)
    {
        $data = [
            'lecture_id' => $request->lecture_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'created_at' => now(),
        ];

        // Xử lý file
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('exercise_files'), $fileName);
            $data['file_path'] = $fileName;
        }

        DB::table('tbl_exercises')->insert($data);

        return redirect()->route('all_exercises')->with('success', 'Exercise created successfully.');
    }

    //Edit
    public function show_edit_exercise($id){
        $lectures = Lecture::all();
        $exercise = Exercises::findOrFail($id);
        
        return view('admin.exercises.edit_exercise', compact('exercise', 'lectures'));
    }

    public function edit_exercise(Request $request, $id){ 
        $exercise = Exercises::findOrFail($id);
        $data = [
            'lecture_id' => $request->lecture_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ];

        // Xử lý file nếu có
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('exercise_files'), $fileName);
            $data['file_path'] = $fileName;
        } else {
            $data['file_path'] = $exercise->file_path;
        }

        DB::table('tbl_exercises')->where('id', $id)->update($data);

        return redirect()->route('all_exercises')->with('success', 'Exercise updated successfully.');
    }

    //Delete
    public function delete_exercise($id){

        DB::table('tbl_exercises')->where('id', $id)->delete();
        return redirect()->route('all_exercises')->with('success', 'Exercise deleted successfully.');
    }
}
