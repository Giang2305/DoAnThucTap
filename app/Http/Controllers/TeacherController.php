<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TeacherController extends Controller
{
//Admin view
    public function all_teacher(){
        $all_teacher = DB::table('tbl_teacher')
                        ->orderByDesc('id')
                        ->get();
        return view('admin.teacher.show_teacher', compact('all_teacher'));
    }

    //Create
    public function show_create_teacher()
    {
        return view('admin.teacher.create_teacher');
    }
    public function create_teacher(Request $request)
    {

        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'department' => $request->department,
            'education_level' => $request->education_level, 
            'experience' => $request->experience,
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
            $data['profile_image'] = $teacher->profile_image;
        }

        DB::table('tbl_teacher')->insert($data);

        return redirect()->route('all_teacher')->with('success', 'Teacher created successfully.');
    }

    //Edit
    public function show_edit_teacher($id){
        $teacher = DB::table('tbl_teacher')->where('id', $id)->first();
        if (!$teacher) {
            return redirect()->route('all_teacher')->with('error', 'Teacher not found.');
        }
        return view('admin.teacher.edit_teacher', compact('teacher'));
    }
    public function edit_teacher(Request $request, $id){ 
        $teacher = DB::table('tbl_teacher')->where('id', $id)->first();
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'department' => $request->department,
            'education_level' => $request->education_level, 
            'experience' => $request->experience,
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
            $data['profile_image'] = $teacher->profile_image;
        }

        DB::table('tbl_teacher')->where('id', $id)->update($data);

        return redirect()->route('all_teacher')->with('success', 'Category edited successfully.');
    }

    //Delete
    public function delete_teacher($id){

        DB::table('tbl_teacher')->where('id', $id)->delete();

        return redirect()->route('all_teacher')->with('success', 'Category deleted successfully.');
    }
}
