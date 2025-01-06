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
<div class="row" style="width: 100%;">
    <div class="container mt-5 col-lg-5" style="border: 2px solid red; border-radius: 3rem; padding: 20px 10px;" >
        <h2 class="text-center mb-4" style="border: 2px solid red; border-radius: 3rem; margin: 0px 150px 0px 150px;">Hồ sơ học viên</h2>
        
        <div class="card mx-auto" style="max-width: 600px;border: 2px solid red; border-radius: 3rem; padding: 20px 10px;">
            <div class="card-body">
                <form method="POST" action="{{ route('student.update', ['id' => $student->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-4">
                        @if($student->profile_image)
                            <img src="{{ asset('/public/images/' . $student->profile_image) }}" alt="Student Profile Image" class="img-thumbnail mt-3 editimg" style="max-width: 300px; border: 1px solid black;  border-radius: 3rem; margin: 0px auto;" tabindex="0">
                        @endif
                        <div class="container"> 
                            <label for="profile_image" class="form-label" style="padding: 10px 0px; text-align: center;"><strong>Ảnh đại diện</strong></label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image">
                        </div>
                       
                    </div>

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $student->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ $student->date_of_birth }}">
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ $student->address }}">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $student->phone }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $student->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $student->start_date }}">
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" class="form-control">
                            <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $student->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="border-radius: 2rem; margin-top: 10px; width: 200px;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
   
    <div class="container mt-5 col-lg-6" style="border: 2px solid red; border-radius: 3rem; padding: 20px 10px;" >
        <h2 class="text-center mb-4" style="border: 2px solid red; border-radius: 3rem; margin: 0px 180px; padding: 5px 0px">Tiến độ học tập</h2>
        <div class="card mx-auto" style="max-width: 900px;border: none; border-radius: 3rem; padding: 20px 10px;">
            <label for="profile_image" class="form-label" style="padding: 10px 0px; text-align: center; font-size: 30px; color: black;"><strong>Khoá học đã tham gia</strong></label> 
            @foreach($courseProgress as $progress)
            <div class="container progressprofile">
                <div class="d-flex justify-content-between align-items-center">
                    <div>            
                        <div class="course-progress mb-4">  
                            <h3 style="color: white;">{{ $progress['course']->Name }}</h3>
                            <p class="lead">{{ $progress['course']->Description }}</p>
                            <div class="d-flex gap-3">
                                <span><i class="fas fa-clock"></i> 20 giờ</span>
                                <span><i class="fas fa-list"></i> {{ $progress['course']->chapters->sum(fn($chapter) => $chapter->lectures->count()) }} bài giảng</span>
                                <span><i class="fas fa-user"></i> Giảng viên: {{$progress['course']->teacher}}</span>
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
        </div>
    </div>
</div>
@endsection