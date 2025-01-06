@extends('Admin.index')
@section('page-title', 'Tiến độ học của: ' . $student->name)
@section('content')
    @foreach($courseProgress as $progress)
    <div class="container top1">
        <div class="d-flex justify-content-between align-items-center">
            <div>            
                <div class="course-progress mb-4">
                    <h3 style="color: white;">{{ $progress['course']->Name }}</h3>
                    <p class="lead">{{ $progress['course']->Description }}</p>
                    <div class="d-flex gap-3">
                        <span><i class="fas fa-clock"></i> 20 giờ</span>
                        <span><i class="fas fa-list"></i> {{ $progress['course']->chapters->sum(fn($chapter) => $chapter->lectures->count()) }} bài giảng</span>
                        <span><i class="fas fa-user"></i> Giảng viên: {{$progress['course']->Teacher}}</span>
                        <span><i class="fas fa-certificate"></i> Chứng chỉ</span>
                        <span><i class="fas fa-star"></i> 4.3 (184 đánh giá)</span>
                    </div>
                    <p class="mt-3">Tiến độ khóa học: {{ $progress['progress'] }}%</p>
                    <div class="progress-bar" style="height: 30px; background-color: #e0e0e0; border-radius: 1rem;">
                        <div class="progress" style="width: {{ $progress['progress'] }}%; background-color: #4caf50; height: 100%;"></div>
                    </div>
                </div>
            </div>
            <div>
                <img style="border-radius: 2rem;" src="{{asset('public/images/' . $progress['course']->Image) }}" alt="Course Image" class="img-fluid">      
            </div>
        </div>
    </div>
    @endforeach
@endsection
