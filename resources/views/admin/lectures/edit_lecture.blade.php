@extends('Admin.index')
@section('page-title', 'Edit Lecture')
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
    textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }
</style>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center">Chỉnh sửa thông tin bài giảng</h2>

        <form action="{{ route('update_lecture', $lecture->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lecture_number" class="form-label">Lecture Number</label>
                    <div class="input-group">
                        <input style="margin-left: 35px" type="number" class="form-control" id="lecture_number" name="lecture_number" value="{{$lecture->lecture_number }}" required>
                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">Bài</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label">Tiểu đề</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $lecture->title }}" placeholder="Nhập tiêu đề" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="chapter_id" class="form-label">Chapter</label>
                    <select class="form-select" id="chapter_id" name="chapter_id" required>
                        <option value="">Chọn chương</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}" 
                                {{ isset($lecture) && $lecture->chapter_id == $chapter->id ? 'selected' : '' }}>
                                {{ $chapter->title }} ({{ $chapter->course->Name }}) <!-- Hiển thị tiêu đề chương và tên khóa học -->
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addChapterModal">
                        + Thêm Chapter
                    </button>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" onchange="toggleFields()">
                        <option value="Video" {{ $lecture->type == 'Video' ? 'selected' : '' }}>Video</option>
                        <option value="Text" {{ $lecture->type == 'Text' ? 'selected' : '' }}>Text</option>
                        <option value="File" {{ $lecture->type == 'File' ? 'selected' : '' }}>File</option>
                    </select>
                </div>
            </div>


            <div class="row mb-3" id="video-url" style="display: none;">
                <div class="col-md-6">
                    <label for="video_url" class="form-label">Video URL</label>
                    <input type="file" class="form-control" id="video_url" name="video_url" required>
                    @if($lecture->video_url)
                    <p class="mt-2">Tên file: <strong>{{ $lecture->video_url}}</strong></p>
                    @else
                        <p class="mt-2 text-muted">Chưa có file nào được upload.</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3" id="text-content" style="display: none;">
                <div class="col-md-12">
                    <label for="text_content" class="form-label">Text Content</label>
                    <textarea class="form-control" id="text_content" name="text_content" required>{{ $lecture->text_content }}</textarea>
                </div>
            </div>
            
            <div class="row mb-3" id="file-upload" style="display: none;">
                <div class="col-md-12">
                    <label for="content_url" class="form-label">Upload File</label>
                    <input type="file" class="form-control" id="content_url" name="content_url" required>
                </div>
                @if($lecture->content_url)
                    <p class="mt-2">Tên file: <strong>{{ $lecture->content_url }}</strong></p>
                @else
                    <p class="mt-2 text-muted">Chưa có file nào được upload.</p>
                @endif
            </div>

            <div class="position-relative row form-group mb-1">
                <div class="col-md-9 col-xl-8 offset-md-2">
                    <a href="{{ route('all_lectures') }}" class="border-0 btn btn-outline-danger mr-1">
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

<!-- Modal Thêm Chapter -->
<div class="modal fade" id="addChapterModal" tabindex="-1" aria-labelledby="addChapterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChapterModalLabel">Thêm Chapter Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{URL::to('save-chapter')}}" method="POST"> 
                    @csrf
                    <div class="mb-3">
                        <label for="chapter_title" class="form-label">Tên Chapter</label>
                        <input type="text" class="form-control" id="chapter_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Khóa Học</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Chọn Khóa Học</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm Chapter</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

 function toggleFields() {
    const type = document.getElementById('type').value;

    const videoUrlSection = document.getElementById('video-url');
    const textContentSection = document.getElementById('text-content');
    const fileUploadSection = document.getElementById('file-upload');

    // Hiển thị các trường tương ứng với type
    videoUrlSection.style.display = type === 'Video' ? 'block' : 'none';
    textContentSection.style.display = type === 'Text' ? 'block' : 'none';
    fileUploadSection.style.display = type === 'File' ? 'block' : 'none';

    // Điều chỉnh required cho các input bên trong từng section
    document.getElementById('video_url').required = type === 'Video';
    document.getElementById('content_url').required = type === 'File';
    document.getElementById('text_content').required = type === 'Text';
}

// Gọi hàm này khi load trang để hiển thị đúng nội dung theo type ban đầu
document.addEventListener('DOMContentLoaded', toggleFields);
</script>

@endsection
