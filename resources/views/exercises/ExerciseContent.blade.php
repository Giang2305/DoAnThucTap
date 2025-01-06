@extends('welcome')

@section('content')
@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container mt-5 top1">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-3 name">Ngôn ngữ {{$course->Name}} </h1>
            <p class="lead">
                {{$course->Description}}
            </p>
            <div class="d-flex gap-3">
                <span><i class="fas fa-clock"></i> 20 giờ</span>
                <span><i class="fas fa-list"></i> {{ $course->chapters->sum(fn($chapter) => $chapter->lectures->count()) }} bài giảng</span>
                <span><i class="fas fa-user"></i> Giảng viên: {{$course->Teacher}}</span>
                <span><i class="fas fa-certificate"></i> Chứng chỉ</span>
                <span><i class="fas fa-star"></i> 4.3 (184 đánh giá)</span>
            </div>
            <a href="{{URL::to('/courses/' . $course->id)}}" class="btn btn-warning mt-3 buttonlecture" style="margin-right: 10px; margin-left: -18px;"><i class="fa-solid fa-rotate-left" ></i> Quay lại</a>
            <a href="{{URL::to('/courses')}}" class="btn btn-info mt-3 buttonlecture"><i class="fa-solid fa-file-export fa-flip-horizontal"></i> Lựa chọn khoá học khác</a>
        </div>
        <div>
            <img style="border-radius: 2rem;" src="{{asset('public/images/' . $course->Image) }}" alt="Thư viện chuẩn C++" class="img-fluid " >
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="d-flex justify-content-center mb-3">
        <!-- Nút chuyển đổi Bài giảng -->
        <a href="{{ route('lectures.show', $lecture->id) }}" 
           class="btn {{ request()->routeIs('lectures.show') ? 'btn-primary' : 'btn-outline-primary' }} mx-2">
            <i class="fas fa-book"></i> Bài giảng
        </a>
        
        <!-- Nút chuyển đổi Bài tập -->
        <a href="{{ route('exercises.show', $lecture->id) }}" 
           class="btn {{ request()->routeIs('exercises.show') ? 'btn-primary' : 'btn-outline-primary' }} mx-2">
            <i class="fas fa-edit"></i> Bài tập
        </a>
    </div>
</div>
<div class="container mt-5 top1">
    <h1>Bài tập cho Bài {{ $lecture->lecture_number }}: {{ $lecture->title }}</h1>

    @if ($exercises->isEmpty())
        <p>Hiện không có bài tập nào cho bài giảng này.</p>
    @else
        <ul class="list-group">
            @foreach ($exercises as $exercise)
                <li class="list-group-item">
                    <h3>Bài tập: {{ $exercise->title }}</h3>
                    <p>{{ $exercise->description }}</p>
                    <iframe 
                        src="{{ asset('public/exercise_files/' . $exercise->file_path) }}" 
                        width="100%" 
                        height="600px" 
                        style="border: none;">
                    </iframe>
                    <div class="container">
                        <h2>Nộp bài tập </h2>
                        <strong style="color: red;">Hạn nộp: {{ $exercise->due_date }}</strong>
                        <form action="{{ route('exercise.submit', $exercise->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="submission_file">Chọn file bài nộp:</label>
                                <input type="file" name="submission_file" id="submission_file" required>
                            </div>
                            @php
                                // Lọc bài nộp của người dùng cho bài tập hiện tại
                                $submissionForExercise = $submission->where('exercises_id', $exercise->id)->first();
                            @endphp

                            @if($submissionForExercise)
                                @if($submissionForExercise->file_path)
                                    <p class="mt-2">Đã nộp: <strong>{{ $submissionForExercise->file_path }}</strong></p>
                                @else
                                    <p class="mt-2 text-muted">Chưa có file nào được upload.</p>
                                @endif
                            @else
                                <p class="mt-2 text-muted">Chưa nộp bài cho bài tập này.</p>
                            @endif
                            <button type="submit" class="btn btn-primary">Nộp bài</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Nút quay lại Bài giảng -->
    <a href="{{ route('lectures.show', $lecture->id) }}" class="btn btn-warning mt-3">
        <i class="fa-solid fa-rotate-left"></i> Quay lại bài giảng
    </a>
</div>
@endsection
