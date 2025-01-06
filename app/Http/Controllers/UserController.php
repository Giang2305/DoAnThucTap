<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Teacher;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
     public function all_account(){
        $all_account = DB::table('tbl_user')
                        ->orderByDesc('id')
                        ->get();
        return view('admin.account.show_accounts', compact('all_account'));
    }

    //Create
    public function show_create_account()
    {
        return view('admin.account.create_account');
    }
    public function create_account(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:tbl_user,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Student,Teacher,Admin', // Kiểm tra role hợp lệ
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Khởi tạo biến để lưu `linked_id`
        $linkedId = null;

        // Tạo bản ghi tương ứng với role
        if ($request->role === 'Student') {
            // Tạo bản ghi trong bảng tbl_students
            $student = Students::create([
                'name' => $request->name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'start_date' => $request->start_date,
                'profile_image' => 'images.jfif',
                'status' => 'Active',
                'created_at' => now(),
            ]);
            $linkedId = $student->id;
        } elseif ($request->role === 'Teacher') {
            // Tạo bản ghi trong bảng tbl_teachers (nếu chưa có)
            $teacher = Teacher::create([                   
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
                'profile_image' => 'images.jfif',
                'status' => 'Active',
                'created_at' => now(),
            ]);
            $linkedId = $teacher->id;
        }

        // Tạo tài khoản trong bảng tbl_user
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => md5($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'linked_id' => $linkedId, // Liên kết ID tương ứng
        ]);

        return redirect()->route('all_account')->with('success', 'Account created successfully.');
    }
   
    //Edit
    public function show_edit_account($id){
        $account = DB::table('tbl_user')->where('id', $id)->first();
        if (!$account) {
            return redirect()->route('all_account')->with('error', 'Account not found.');
        }
        return view('admin.account.edit_account', compact('account'));
    }
    public function edit_account(Request $request, $id)
    {
        $account = DB::table('tbl_user')->where('id', $id)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found.');
        }

        $linkedId = $account->linked_id;

        // Kiểm tra quyền hiện tại và quyền mới
        if ($request->role === 'Teacher') {
            // Nếu chuyển sang Teacher và chưa có bản ghi trong tbl_teacher
            if (!DB::table('tbl_teacher')->where('id', $linkedId)->exists()) {
                // Tạo dữ liệu giáo viên mặc định
                $teacherId = DB::table('tbl_teacher')->insertGetId([
                    'name' => $request->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                    // Thêm các trường mặc định khác nếu cần
                ]);
                $linkedId = $teacherId;
            }
        } elseif ($request->role === 'Student') {
            // Nếu chuyển sang Student và chưa có bản ghi trong tbl_students
            if (!DB::table('tbl_students')->where('id', $linkedId)->exists()) {
                // Tạo dữ liệu học sinh mặc định
                $studentId = DB::table('tbl_students')->insertGetId([
                    'name' => $request->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                    // Thêm các trường mặc định khác nếu cần
                ]);
                $linkedId = $studentId;
            }
        }

        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status,
            'linked_id' => $linkedId, // Cập nhật lại linked_id nếu thay đổi
        ];

        // Cập nhật thông tin vào bảng tbl_user
        DB::table('tbl_user')->where('id', $id)->update($data);

        return redirect()->route('all_account')->with('success', 'Account edited successfully.');
    }

    //Delete
    public function delete_account($id){

        DB::table('tbl_user')->where('id', $id)->delete();

        return redirect()->route('all_account')->with('success', 'Account deleted successfully.');
    }
}
