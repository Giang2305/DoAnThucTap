@extends('Admin.index')
@section('page-title', 'Edit Exercise')
@section('content')

<style>
    /* Styling */
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
        <h2 class="mb-4 text-center">Sửa bài tập</h2>

        <form action="{{ route('update_exercise',  $exercise->id) }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Tên bài tập</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$exercise->title }}"  style="border: 1px solid black; border-radius: 10px;"required>
                </div>
                <div class="col-md-6">
                    <label for="lecture_id" class="form-label">Bài giảng: <strong>{{ $exercise->lecture->title ?? 'No Lecture' }}</strong> </label>
                    <select class="form-select" id="lecture_id" name="lecture_id" required>
                        <option value="">Chọn bài giảng</option>
                        @foreach($lectures as $lecture)
                            <option value="{{ $lecture->id }}">{{ $lecture->title }}</option>
                        @endforeach       
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="2" value="{{$exercise->description }}"  style="border: 1px solid black; border-radius: 1rem;">{{$exercise->description }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="file_path" class="form-label">Tệp đính kèm</label>
                    <input type="file" class="form-control" id="file_path" name="file_path" style="border: 1px solid black; border-radius: 8px;">
                    @if($exercise->file_path)
                    <p class="mt-2">Tên file: <strong>{{$exercise->file_path}}</strong></p>
                    @else
                        <p class="mt-2 text-muted">Chưa có file nào được upload.</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="due_date" class="form-label">Ngày hết hạn</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="{{$exercise->due_date }}"  style="border: 1px solid black; border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status" value="{{$exercise->status }}"   required>
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
