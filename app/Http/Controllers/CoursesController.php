<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\UserProgress;
use App\Models\Lecture;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DB;

class CoursesController extends Controller
{

//User view
    public function index() {
        $all_courses = DB::table('tbl_courses')
                        ->orderByDesc('id')
                        ->get();
        return view('courses.all_courses', compact('all_courses'));
    }

    public function detail($id)
    {
        $userId = Session::get('user_id');  // Lấy ID người dùng đăng nhập
        $course = Course::with('chapters.lectures.exercises')->findOrFail($id);

        // Kiểm tra xem người dùng đã đăng ký khóa học chưa
        $isRegistered = UserProgress::where('users_id', $userId)
                                     ->where('courses_id', $id)
                                     ->exists();

        // Tính toán tiến độ khóa học
        $completedProgress = UserProgress::where('users_id', $userId)
                                          ->where('courses_id', $id)
                                          ->where('status', 'Completed')
                                          ->count();

        // Tính tổng số bài giảng và bài tập
        $totalItems = $course->chapters->sum(fn($chapter) => $chapter->lectures->count() + $chapter->lectures->sum(fn($lecture) => $lecture->exercises->count()));
        
        // Tính tiến độ
        $progress = $totalItems > 0 ? ($completedProgress / $totalItems) * 100 : 0;

        return view('courses.detail_courses', compact('course', 'isRegistered', 'progress'));
    }

    public function registerCourse($id)
    {
        $userId = Session::get('user_id'); // Lấy ID người dùng đăng nhập hiện tại

        // Kiểm tra xem người dùng đã đăng ký khóa học này chưa
        $isRegistered = UserProgress::where('users_id', $userId)
                                     ->where('courses_id', $id)
                                     ->exists();

        if (!$isRegistered) {
            // Lấy khóa học cùng các chương, bài giảng và bài tập liên quan
            $course = Course::with('chapters.lectures.exercises')->find($id);

            foreach ($course->chapters as $chapter) {
                foreach ($chapter->lectures as $lecture) {
                    // Lưu tiến độ cho bài giảng
                    UserProgress::create([
                        'users_id' => $userId,
                        'lectures_id' => $lecture->id,
                        'courses_id' => $id,
                        'progress' => 0,
                        'status' => 'Incomplete',
                    ]);

                   foreach ($lecture->exercises as $exercise) {
                        UserProgress::create([
                            'users_id' => $userId,
                            'lectures_id' => $lecture->id, // Truyền giá trị lectures_id từ bài giảng hiện tại
                            'exercises_id' => $exercise->id,
                            'courses_id' => $id,
                            'progress' => 0,
                            'status' => 'Incomplete',
                        ]);
                    }
                }
            }

            return redirect()->back()->with('message', 'Bạn đã đăng ký khóa học thành công');
        }

        return response()->json(['message' => 'Bạn đã đăng ký khóa học này trước đó'], 400);
    }


    
//Admin view
    public function all_courses(){
        $all_courses = DB::table('tbl_courses')
                        ->orderByDesc('id')
                        ->get();
        return view('admin.courses.show_courses', compact('all_courses'));
    }

    //Create
    public function show_create_courses()
    {
        $userId = session('user_id');
        $teacher = Teacher::all();
        return view('admin.courses.create_courses', compact('teacher'));
    }
    public function create_courses(Request $request)
    {
        $data = [
            'Name' => $request->name,
            'Description' => $request->description,
            'Teacher' => $request->teacher,
            'Price' => $request->price,
            'is_active' => $request->is_active,
            'created_at' => now(),
        ];

        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName(); 
        $file->move(public_path('images'), $fileName); 
        $data['Image'] = $fileName;
        }

        DB::table('tbl_courses')->insert($data);

        return redirect()->route('all_courses')->with('success', 'Course created successfully.');
    }

    //Edit
    public function show_edit_courses($id){
        $teacher = Teacher::all();
        $course = DB::table('tbl_courses')->where('id', $id)->first();
        if (!$course) {
            return redirect()->route('all_courses')->with('error', 'Courses not found.');
        }
        return view('admin.courses.edit_courses', compact('course', 'teacher'));
    }
    public function edit_courses(Request $request, $id){ 
        $data = [
            'Name' => $request->name,
            'Description' => $request->description,
            'Teacher' => $request->teacher,
            'Price' => $request->price,
            'is_active' => $request->is_active,
            'created_at' => now(),
        ];

        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName(); 
        $file->move(public_path('images'), $fileName);
        $data['Image'] = $fileName;
        }

        DB::table('tbl_courses')->where('id', $id)->update($data);

        return redirect()->route('all_courses')->with('success', 'Category edited successfully.');
    }

    //Delete
    public function delete_courses($id){

        DB::table('tbl_courses')->where('id', $id)->delete();

        return redirect()->route('all_courses')->with('success', 'Category deleted successfully.');
    }    
}
