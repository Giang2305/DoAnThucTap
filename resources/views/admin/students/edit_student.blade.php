@extends('Admin.index')
@section('page-title', 'Edit Student')
@section('content')
<style>
    .form-control {
        border: 2px solid #ced4da;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px; 
    }

    .form-control:focus {
        border-color: #007bff; 
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none; 
    }

    textarea.form-control {
        resize: vertical; 
        min-height: 150px; 
    }

    img {
        transition: transform 1s ease;
    }
    img:hover {
        transform: scale(1.7) translateX(30px);
    }
</style>
@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center">Chỉnh sửa thông tin học viên</h2>

        <form action="{{ route('update_student', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="profile_image" class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image">
                @if($student->profile_image)
                    <img src="{{ asset('/public/images/' . $student->profile_image) }}" alt="Student Profile Image" class="img-thumbnail mt-3 editimg" style="max-width: 200px;" tabindex="0">
                @endif
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Tên học viên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" placeholder="Sửa tên học viên" required>
                </div>
                <div class="col-md-6">
                    <label for="gender" class="form-label">Giới tính</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $student->date_of_birth }}">
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $student->address }}" placeholder="Nhập địa chỉ mới" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">SĐT</label>
                    <input type="number" class="form-control" id="phone" name="phone" value="{{ $student->phone }}" placeholder="Nhập SĐT" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}" placeholder="Nhập Email" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Ngày đăng ký học</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $student->start_date }}">
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Tình trạng hoạt động</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Đang học</option>
                        <option value="Inactive" {{ $student->status == 'Inactive' ? 'selected' : '' }}>Đã nghỉ học</option>
                    </select>
                </div>
            </div>

            <div class="position-relative row form-group mb-1">
                <div class="col-md-9 col-xl-8 offset-md-2">
                    <a href="{{ route('all_students') }}" class="border-0 btn btn-outline-danger mr-1">
                        <span class="btn-icon-wrapper pr-1 opacity-8">
                            <i class="fa fa-times fa-w-20"></i>
                        </span>
                        <span>Huỷ</span>
                    </a>

                    <button type="submit" class="btn-shadow btn-hover-shine btn bg-gradient-warning">
                        <span class="btn-icon-wrapper pr-2 opacity-8">
                            <i class="fa fa-edit fa-w-20"></i>
                        </span>
                        Sửa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
