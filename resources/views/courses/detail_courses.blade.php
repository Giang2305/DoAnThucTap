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
            @if(!$isRegistered)
                <form method="POST" action="{{ url('courses/' . $course->id . '/register') }}">
                    @csrf <!-- Thêm CSRF token -->
                    <button type="submit" class="btn btn-success mt-3 button">
                        <i class="fa-solid fa-right-to-bracket"></i> Đăng ký chỉ với {{ $course->Price }}
                    </button>
                </form>
                <form method="POST" action="{{url('courses/' . $course->id . '/save-cart')}}">
                    @csrf
                    <button type="submit" class="btn btn-warning mt-3 button">
                        <i class='fas fa-cart-plus' style='font-size:24px;'></i> Thêm vào giỏ hàng
                    </button>
                </form>
            @endif


            <!-- Hiển thị tiến độ -->
            <div class="progress-container mt-3">
                <h2 style="color: white;">Tiến độ khóa học</h2>
                <p>Tiến độ hiện tại của bạn trong khóa học: {{ round($progress, 2) }}%</p>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ round($progress, 2) }}%;"></div>
                </div>
            </div>
        </div>
        <div>
            <img style="border-radius: 2rem;" src="{{asset('public/images/' . $course->Image) }}" alt="Thư viện chuẩn C++" class="img-fluid " >
        </div>
        <button id="toggle-theme" class="btn" style="padding: 0px 0px 0px -100px"><i class="fa-brands fa-medapps fa-2xl"></i></button>
    </div>
</div>

<div class="container">
    <h1 class="named">Nội dung khóa học</h1>
    <p>{{ count($course->chapters) }} Chương • {{ $course->chapters->sum(fn($chapter) => $chapter->lectures->count()) }} bài giảng • {{ $course->chapters->sum(fn($chapter) => $chapter->lectures->sum(fn($lecture) => $lecture->exercises->count())) }} bài tập</p>
    <div class="accordion" id="courseAccordion">
        @foreach ($course->chapters as $chapter)
            <div class="card">
                <div class="card-header" id="heading-{{ $chapter->id }}">
                    <h5 class="mb-0">
                        <button class="btn btn-link named" type="button" data-toggle="collapse" 
                            data-target="#collapse-{{ $chapter->id }}" 
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                            aria-controls="collapse-{{ $chapter->id }}">
                            {{ $chapter->title }} ({{ $chapter->lectures->count() }} bài giảng) 
                        </button>
                    </h5>
                </div>
                
                <div id="collapse-{{ $chapter->id }}" 
                    class="collapse {{ $loop->first ? 'show' : '' }}" 
                    aria-labelledby="heading-{{ $chapter->id }}" 
                    data-parent="#courseAccordion">
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($chapter->lectures as $lecture)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @if ($isRegistered)
                                        <!-- Hiển thị link khi đã đăng ký -->
                                        <a href="{{ route('lectures.show', $lecture->id) }}" class="named">
                                            Bài {{ $lecture->lecture_number }}: {{ $lecture->title }}
                                            <span class="badge badge-primary badge-pill">
                                                {{ $lecture->duration }} phút
                                            </span>
                                        </a>
                                    @else
                                        <!-- Thông báo yêu cầu đăng ký -->
                                        <span class="named">Bài {{ $lecture->lecture_number }}: {{ $lecture->title }}</span>
                                        <span class="badge badge-secondary badge-pill">Đăng ký để truy cập</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@endsection('content')
