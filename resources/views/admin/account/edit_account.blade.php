@extends('Admin.index')
@section('page-title', 'Edit Account')
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
        <h2 class="mb-4 text-center">Chỉnh sửa thông tin tài khoản</h2>

        <form action="{{ route('update_account', $account->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Tên người dùng</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $account->name }}" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Tên tài khoản</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $account->username }}" placeholder="Nhập tên tài khoản" required>
                </div>                
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">Quyền tài khoản</label>                    
                    <select class="form-select" id="role" name="role" required>
                        <option value="Student" {{ $account->role == 'Student' ? 'selected' : '' }}>Học viên</option>
                        <option value="Teacher" {{ $account->role == 'Teacher' ? 'selected' : '' }}>Giảng viên</option>
                        <option value="Admin" {{ $account->role == 'Admin' ? 'selected' : '' }}>Quản trị viên</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Tình trạng hoạt động</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Active" {{ $account->status == 'Active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="Inactive" {{ $account->status == 'Inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    </select>
                </div>
            </div>

             <div class="position-relative row form-group mb-1">
                <div class="position-relative row form-group mb-1">
                    <div class="col-md-9 col-xl-8 offset-md-2">
                        <a href="{{ route('all_account') }}" class="border-0 btn btn-outline-danger mr-1">
                            <span class="btn-icon-wrapper pr-1 opacity-8">
                                <i class="fa fa-times fa-w-20"></i>
                            </span>
                            <span>Huỷ</span>
                        </a>

                        <button type="submit" class="btn-shadow btn-hover-shine btn bg-gradient-warning">
                            <span class="btn-icon-wrapper pr-2 opacity-8">
                                <i class="fa fa-download fa-w-20"></i>
                            </span>
                            Sửa
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
