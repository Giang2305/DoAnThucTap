@extends('Admin.index')
@section('page-title', 'Create Exercise')
@section('content')
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
        <h2 class="mb-4 text-center">Tạo bài tập mới</h2>

        <form action="{{ route('save_exercises') }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Tên bài tập</label>
                    <input type="text" class="form-control" id="title" name="title" required style="border: 1px solid black; border-radius: 10px;">
                </div>
                <div class="col-md-6">
                    <label for="lecture_id" class="form-label">Bài giảng</label>
                    <select class="form-select" id="lecture_id" name="lecture_id" required>
                        <option value="">Chọn bài giảng</option>
                        @foreach($lectures as $lecture)
                            <option value="{{ $lecture->id }}">Bài {{ $lecture->lecture_number}} {{ $lecture->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="2" style="border: 1px solid black; border-radius: 1rem;"></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="file_path" class="form-label">Tệp đính kèm</label>
                    <input type="file" class="form-control" id="file_path" name="file_path" style="border: 1px solid black; border-radius: 8px;">
                </div>
                <div class="col-md-6">
                    <label for="due_date" class="form-label">Ngày hết hạn</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" style="border: 1px solid black; border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status"  required>
                      <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="position-relative row form-group mb-1">
                <div class="col-md-9 col-xl-8 offset-md-2">
                    <a href="{{ route('all_exercises') }}" class="border-0 btn btn-outline-danger mr-1">
                        <span class="btn-icon-wrapper pr-1 opacity-8">
                            <i class="fa fa-times fa-w-20"></i>
                        </span>
                        Huỷ
                    </a>
                    <button type="submit" class="btn-shadow btn-hover-shine btn bg-gradient-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-8">
                            <i class="fa fa-save fa-w-20"></i>
                        </span>
                        Tạo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
