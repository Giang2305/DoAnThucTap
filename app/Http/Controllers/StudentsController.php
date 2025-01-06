<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\UserProgress;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\User;
use DB;

class StudentsController extends Controller
{
    //User View
    public function profile($id) {
    $course = Course::all();
    $linkedId = session('linked_id');
    
    // Lấy thông tin học viên đang đăng nhập dựa trên linked_id
    $student = Students::findOrFail($linkedId);
    
    // Lấy user liên quan từ thông tin học viên
    $user = $student->user; // Đảm bảo rằng $student có liên kết với user
    
    // Lấy tất cả các khóa học mà học viên đã đăng ký (dựa trên user_id)
    $userProgress = UserProgress::where('users_id', $user->id)
                                ->with('course.chapters.lectures.exercises') // Liên kết với khóa học, chương, bài giảng và bài tập
                                ->get();

    $courseProgress = [];
    $processedCourses = [];

    foreach ($userProgress as $progress) {
        $course = $progress->course;

        // Kiểm tra xem khóa học này đã được tính chưa
        if (in_array($course->id, $processedCourses)) {
            continue;
        }

        // Đánh dấu khóa học đã được tính
        $processedCourses[] = $course->id;

        // Tính toán số bài giảng và bài tập đã hoàn thành trong khóa học
        $completedLectures = UserProgress::where('users_id', $user->id)
                                         ->where('courses_id', $course->id)
                                         ->whereNotNull('lectures_id') // chỉ tính các bài giảng
                                         ->where('status', 'Completed') // Đảm bảo chỉ tính các bài giảng đã hoàn thành
                                         ->count();

        $completedExercises = UserProgress::where('users_id', $user->id)
                                          ->where('courses_id', $course->id)
                                          ->whereNotNull('exercises_id') // chỉ tính các bài tập
                                          ->where('status', 'Completed') // Đảm bảo chỉ tính các bài tập đã hoàn thành
                                          ->count();

        // Tính tổng số bài giảng và bài tập trong khóa học
        $totalLectures = $course->chapters->sum(fn($chapter) => $chapter->lectures->count());
        $totalExercises = $course->chapters->sum(fn($chapter) => $chapter->lectures->sum(fn($lecture) => $lecture->exercises->count()));

        // Tổng số bài giảng và bài tập
        $totalItems = $totalLectures + $totalExercises;

        // Tính tiến độ (bài giảng và bài tập đã hoàn thành)
        $completedProgress = $completedLectures + $completedExercises;
        $progressPercentage = $totalItems > 0 ? ($completedProgress / $totalItems) * 100 : 0;

        $courseProgress[] = [
            'course' => $course,
            'progress' => round($progressPercentage, 2),  // Làm tròn phần trăm tiến độ
        ];
    }

    // Truyền dữ liệu khóa học và học viên đến view
    return view('user_profile.show', compact('student', 'courseProgress'));
}

    // public function profile($id){
    //     $course = Course::all();
    //     $linkedId = session('linked_id');
    //     $student = Students::findOrFail($linkedId);

    //     return view('user_profile.show', compact('student','course'));
    // }

    public function update_profile(Request $request, $id){
        $student = Students::findOrFail($id);
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'profile_image' => $request->profile_image,
            'status' => $request->status,
            'created_at' => now(),
        ];

        if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $fileName = $file->getClientOriginalName(); 
        $file->move(public_path('images'), $fileName);
        $data['profile_image'] = $fileName;
        }else{
            $data['profile_image'] = $student->profile_image;
        }

        $student->update($data);

        session([
            'name' => $student->name,
            'profile_image' => $student->profile_image,
        ]);

        return redirect()->route('student.profile', ['id' => $id])->with('success', 'Profile updated successfully');
    }

    //Admin view
    public function all_students(){
        $all_students = DB::table('tbl_students')
                        ->orderByDesc('id')
                        ->get();
        return view('admin.students.show_students', compact('all_students'));
    }

    public function showStudentProgress($id)
    {
        // Lấy thông tin học viên dựa trên student_id
        $student = Students::findOrFail($id);

        // Tìm user_id dựa trên linked_id trong bảng tbl_user
        $user = User::where('linked_id', $student->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng cho học viên này.');
        }

        // Lấy tất cả các khóa học mà học viên đã đăng ký (dựa trên user_id)
        $userProgress = UserProgress::where('users_id', $user->id)
                                    ->with('course.chapters.lectures.exercises') // Liên kết với khóa học, chương, bài giảng và bài tập
                                    ->get();

        $courseProgress = [];

        // Mảng để kiểm tra xem khóa học đã được tính hay chưa
        $processedCourses = [];

        foreach ($userProgress as $progress) {
            $course = $progress->course;

            // Kiểm tra xem khóa học này đã được tính chưa
            if (in_array($course->id, $processedCourses)) {
                continue;
            }

            // Đánh dấu khóa học đã được tính
            $processedCourses[] = $course->id;

            // Tính toán số bài giảng và bài tập đã hoàn thành trong khóa học
            $completedLectures = UserProgress::where('users_id', $user->id)
                                             ->where('courses_id', $course->id)
                                             ->whereNotNull('lectures_id') // chỉ tính các bài giảng
                                             ->where('status', 'Completed') // Đảm bảo chỉ tính các bài giảng đã hoàn thành
                                             ->count();

            $completedExercises = UserProgress::where('users_id', $user->id)
                                              ->where('courses_id', $course->id)
                                              ->whereNotNull('exercises_id') // chỉ tính các bài tập
                                              ->where('status', 'Completed') // Đảm bảo chỉ tính các bài tập đã hoàn thành
                                              ->count();

            // Tính tổng số bài giảng và bài tập trong khóa học
            $totalLectures = $course->chapters->sum(fn($chapter) => $chapter->lectures->count());
            $totalExercises = $course->chapters->sum(fn($chapter) => $chapter->lectures->sum(fn($lecture) => $lecture->exercises->count()));

            // Tổng số bài giảng và bài tập
            $totalItems = $totalLectures + $totalExercises;

            // Tính tiến độ (bài giảng và bài tập đã hoàn thành)
            $completedProgress = $completedLectures + $completedExercises;  // Tổng bài giảng và bài tập đã hoàn thành
            $progressPercentage = $totalItems > 0 ? ($completedProgress / $totalItems) * 100 : 0;

            $courseProgress[] = [
                'course' => $course,
                'progress' => round($progressPercentage, 2),  // Làm tròn phần trăm tiến độ
            ];
        }

        // Trả về view với thông tin tiến độ của học viên
        return view('admin.progress.progress', compact('student', 'courseProgress'));
    }



    //Create
    public function show_create_student()
    {
        return view('admin.students.create_student');
    }
    public function create_student(Request $request)
    {
        
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'profile_image' => $request->profile_image,
            'status' => $request->status,
            'created_at' => now(),
        ];

        if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $fileName = $file->getClientOriginalName(); 
        $file->move(public_path('images'), $fileName); 
        $data['profile_image'] = $fileName;
        }else{
            $data['profile_image'] = $student->profile_image;
        }

        DB::table('tbl_students')->insert($data);

        return redirect()->route('all_students')->with('success', 'Student created successfully.');
    }

    //Edit
    public function show_edit_student($id){
        $student = DB::table('tbl_students')->where('id', $id)->first();
        if (!$student) {
            return redirect()->route('all_students')->with('error', 'Student not found.');
        }
        return view('admin.students.edit_student', compact('student'));
    }
    public function edit_student(Request $request, $id){ 
        $student = DB::table('tbl_students')->where('id', $id)->first();
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'profile_image' => $request->profile_image,
            'status' => $request->status,
            'created_at' => now(),
        ];

        if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $fileName = $file->getClientOriginalName(); 
        $file->move(public_path('images'), $fileName);
        $data['profile_image'] = $fileName;
        }else{
            $data['profile_image'] = $student->profile_image;
        }

        DB::table('tbl_students')->where('id', $id)->update($data);

        return redirect()->route('all_students')->with('success', 'Student edited successfully.');
    }

    //Delete
    public function delete_student($id){

        DB::table('tbl_students')->where('id', $id)->delete();

        return redirect()->route('all_students')->with('success', 'Student deleted successfully.');
    }
}
