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
<!-- Courses Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Khoá học</h6>
                <h1 class="mb-5">Tất cả khoá học</h1>
            </div>
            <div class="row g-4 justify-content-center">
               @foreach($all_courses as $courses)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="course-item bg-light">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid" src="{{ asset('public/images/' . $courses->Image) }}" alt="" style="object-fit: cover; width: 100%; height: 200px;">
                            <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-4">
                                <a href=" {{URL::to('/courses/'. $courses->id)}}" class="flex-shrink-0 btn btn-sm btn-primary px-3 border-end" style="border-radius: 30px 0 0 30px;">Chi tiết</a>
                                <form id="add-to-cart-{{ $courses->id }}" method="POST" action="{{ url('courses/' . $courses->id . '/save-cart') }}">
                                    @csrf
                                    <a href="javascript:void(0)" 
                                       onclick="document.getElementById('add-to-cart-{{ $courses->id }}').submit();" 
                                       class="flex-shrink-0 btn btn-sm btn-primary px-3" 
                                       style="border-radius: 0 30px 30px 0;">
                                       Thêm vào giỏ
                                    </a>
                                </form>
                            </div>
                        </div>
                        <div class="text-center p-4 pb-0">
                            <h3 class="mb-0">{{$courses->Price }}</h3>
                            <div class="mb-3">
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small>(123)</small>
                            </div>
                            <h5 class="mb-4">{{ $courses->Name }}</h5>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-user-tie text-primary me-2"></i>{{ $courses->Teacher }}</small>
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-clock text-primary me-2"></i>{{ $courses->created_at }}</small>
                            <small class="flex-fill text-center py-2"><i class="fa fa-door-open text-primary me-2"></i>{{ $courses->is_active }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Courses End -->
    @endsection