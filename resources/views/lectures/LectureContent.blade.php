@extends('welcome')

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
<div class="container-fluid p-3">
    <div class="row">  
         <div class="col-lg-9"  >
            <h1>Bài {{ $lecture->lecture_number }}: {{ $lecture->title }}</h1>
            <p>Loại: <strong>{{ $lecture->type }}</strong></p>
            @if ($lecture->type === 'Video')
                <div class="video-container">
                    <video controls width="100%" height="600px">
                        <source src="{{ asset('/public/video_url/' . $lecture->video_url) }}" type="video/mp4">
                        Trình duyệt của bạn không hỗ trợ phát video.
                    </video>
                </div>
            @elseif ($lecture->type === 'File')
                <iframe 
                    src="{{ asset('/public/lectureUrl/' . $lecture->content_url) }}" 
                    width="100%" 
                    height="600px" 
                    style="border: none;">
                </iframe>
            @elseif ($lecture->type === 'Text')
                <div class="text-content">
                    <p>{{ $lecture->text_content }}</p>
                </div>
            @endif

            <!-- Form để đánh dấu bài giảng là hoàn thành -->
            <form action="{{ route('lectures.complete', $lecture->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success mt-3">Hoàn thành bài giảng</button>
            </form>

        </div>

        <div class="col-lg-3 accordion" id="courseAccordion">
            <h1>Bài giảng khác:</h1>
            @foreach ($course->chapters as $chapter)
                <div class="card">
                    <div class="card-header" id="heading-{{ $chapter->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link {{ $chapter->lectures->contains('id', $lecture->id) ? 'active bg-gradient-primary text-danger' : '' }}" type="button" data-toggle="collapse" 
                                data-target="#collapse-{{ $chapter->id }}" 
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                aria-controls="collapse-{{ $chapter->id }}"> {{ $chapter->title }} ({{ $chapter->lectures->count() }} bài giảng)
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-{{ $chapter->id }}" 
                        class="collapse {{ $chapter->lectures->contains('id', $lecture->id) ? 'show' : '' }}" 
                        aria-labelledby="heading-{{ $chapter->id }}" 
                        data-parent="#courseAccordion">
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($chapter->lectures as $lec)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('lectures.show', $lec->id) }}" 
                                           class="{{ $lec->id == $lecture->id ? 'active bg-gradient-primary text-danger' : '' }}" style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            Bài {{ $lec->lecture_number }}: {{ $lec->title }}
                                            <span class="badge badge-primary badge-pill">
                                                {{ $lec->duration }} phút
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
