<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Lecture;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Session;

class LecturesController extends Controller
{
    //User View
    public function show($id){
        $lecture = Lecture::findOrFail($id);
        $chapter = $lecture->chapter;
        $course = $lecture->chapter->course;
        return view('lectures.LectureContent', compact('lecture','course','chapter')); 
    }
    public function markAsCompleted($id)
    {
        // Lấy thông tin bài giảng
        $lecture = Lecture::findOrFail($id);
        
        // Kiểm tra nếu bài giảng đã được đánh dấu là hoàn thành
        $userProgress = UserProgress::where('users_id', Session::get('user_id'))
                                    ->where('lectures_id', $lecture->id)
                                    ->first();
        
        if ($userProgress) {
            // Nếu đã có thông tin tiến độ, cập nhật trạng thái là "Completed"
            $userProgress->status = 'Completed';
            $userProgress->progress = 100;
            $userProgress->save();
        } else {
            // Nếu chưa có thông tin tiến độ, tạo mới một bản ghi với trạng thái "Completed"
            UserProgress::create([
                'users_id' => Session::get('user_id'),
                'lectures_id' => $lecture->id,
                'status' => 'Completed',
                'progress' => 100, // Tiến độ 100% khi hoàn thành
            ]);
        }

        return redirect()->back()->with('message', 'Bài giảng đã hoàn thành.');
    }


    //Admin view
    public function all_lectures(){
        $chapters = Chapter::with('course')->get();
        $all_lectures = Lecture::with('chapter.course')->get();
        return view('admin.lectures.show_lectures', compact('all_lectures','chapters'));
    }

    //Create
    public function show_create_lecture()
    {
        $courses = Course::all();
        $chapters = Chapter::all();
        return view('admin.lectures.create_lecture', compact( 'courses','chapters'));
    }
    public function create_lecture(Request $request)
    {
        $lecture = Lecture::all();
        $data = [
            'lecture_number' => $request->lecture_number,
            'title' => $request->title,
            'type' => $request->type,
            'content_url' => $request->content_url,
            'text_content' => $request->text_content,
            'video_url' => $request->video_url,
            'chapter_id' => $request->chapter_id,
            'created_at' => now(),
        ];
        //file
        if ($request->hasFile('content_url')) {
            $file = $request->file('content_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('lectureUrl'), $fileName);
            $data['content_url'] = $fileName;
        }
        //video
        if ($request->hasFile('video_url')) {
            $file = $request->file('video_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('video_url'), $fileName);
            $data['video_url'] = $fileName;
        } 

        DB::table('tbl_lectures')->insert($data);

        return redirect()->route('all_lectures')->with('success', 'Leture created successfully.');
    }

    //Edit
    public function show_edit_lecture($id){
        $courses = Course::all();
        $lecture = DB::table('tbl_lectures')->where('id', $id)->first();
        $chapters = Chapter::with('course')->get();
        if (!$lecture) {
            return redirect()->route('all_lectures')->with('error', 'Leture not found.');
        }
        return view('admin.lectures.edit_lecture', compact('courses','lecture', 'chapters'));
    }
    public function edit_lecture(Request $request, $id){ 
        $lecture = DB::table('tbl_lectures')->where('id', $id)->first();
        $data = [
            'lecture_number' => $request->lecture_number,
            'title' => $request->title,
            'type' => $request->type,
            'content_url' => $request->content_url,
            'text_content' => $request->text_content,
            'video_url' => $request->video_url,
            'chapter_id' => $request->chapter_id,
            'created_at' => now(),
        ];
        //file
        if ($request->hasFile('content_url')) {
            $file = $request->file('content_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('lectureUrl'), $fileName);
            $data['content_url'] = $fileName;
        } else {
            $data['content_url'] = $lecture->content_url; // Giữ nguyên tên file cũ nếu không upload mới
        }
        //video
        if ($request->hasFile('video_url')) {
            $file = $request->file('video_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('video_url'), $fileName);
            $data['video_url'] = $fileName;
        } else {
            $data['video_url'] = $lecture->video_url; // Giữ nguyên tên file cũ nếu không upload mới
        }

        DB::table('tbl_lectures')->where('id', $id)->update($data);

        return redirect()->route('all_lectures')->with('success', 'Lecture edited successfully.');
    }

    //Delete
    public function delete_lecture($id){

        DB::table('tbl_lectures')->where('id', $id)->delete();

        return redirect()->route('all_lectures')->with('success', 'Lecture deleted successfully.');
    }

    public function save_chapter(Request $request) {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:tbl_courses,id',
        ]);

        // Kiểm tra xem chapter đã tồn tại chưa
        $existingChapter = DB::table('tbl_chapters')
            ->where('title', $validatedData['title'])
            ->where('course_id', $validatedData['course_id'])
            ->first();

        if ($existingChapter) {
            return redirect()->back()->with('error', 'Chapter với tiêu đề này đã tồn tại trong khóa học này.');
        };
        // Thêm dữ liệu mới nếu không trùng lặp
        DB::table('tbl_chapters')->insert([
            'title' => $validatedData['title'],
            'course_id' => $validatedData['course_id'],
        ]);

        return redirect()->back()->with('success', 'Chapter đã được thêm thành công.');
    }


}

