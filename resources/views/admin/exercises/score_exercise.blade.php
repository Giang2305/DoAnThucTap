@extends('Admin.index')
@section('page-title', 'Score Students')
@section('content')
<style> 
    table, th, td {
      border: 1px solid black !important;
      border-collapse: collapse !important;
      padding-top: 20px !important;
    }
</style>

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

<div class="container mt-3 mb-3" style="border:2px solid black; border-radius: 1rem; text-align: center; background-color: #ffffff;">
    <h2>Bài tập: {{ $exercise->title }}</h2>
    <p><strong>Mô tả: 
        @if($exercise->description != Null) 
             {{ $exercise->description }}
        @else
            Chưa có mô tả
        @endif
   </strong></p>
    @if($exercise->file_path != Null)
        <iframe 
            src="{{ asset('public/exercise_files/' . $exercise->file_path) }}" 
            width="1200px" 
            height="400px" 
            style="border: none;">
        </iframe>
    @else
        <p>Chưa có file bài tập nào upload lên</p>
    @endif
        <h3>Danh sách học viên đã nộp bài</h3>
    @if($submissions->isEmpty())
        <p>Chưa có học viên nào nộp bài.</p>
    @else
        <table class="table" style="border: 1px solid black; border-radius: 1 rem; ">
            <thead style="border: 1px solid black;">
                <tr style="border: 1px solid black;">
                    <th>Tên học viên</th>
                    <th>File đã nộp</th>
                    <th>Điểm</th>
                    <th>Xếp loại</th>
                    <th>Nhận xét</th>
                    <th>Trạng thái</th>
                    <th>Chấm điểm</th>
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->user->name }}</td> <!-- Hiển thị tên học viên -->
                        <td>
                            @if($submission->file_path)
                                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewFileModal" 
                                   data-file="{{asset('storage/app/public/submissions/' . $submission->file_path)}}">
                                    Xem file
                                </a><br>
                                <span><strong>{{ $submission->file_path }}</strong></span>
                            @else
                                Chưa nộp file
                            @endif
                        </td>

                        <td>
                             @if($submission->score != Null)
                                <h2><strong>{{ $submission->score }}</strong></h2>
                            @else
                                Null
                            @endif
                        </td>
                        <td>
                            <h2><strong>{{ $submission->grading }}</strong></h2>
                        </td>
                         <td>
                            <p>{{ $submission->feedback }}</p>
                        </td>
                        <td>
                            @if($submission->status == 'Incomplete')
                                <a class="btn btn-warning">Chưa chấm</a>
                            @else
                                <a class="btn btn-success">Đã chấm xong</a>
                            @endif
                        </td>
                        <td>
                            @if($submission->status == 'Incomplete')
                                <a href="javascript:void(0)" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scoreModal" 
                                   data-file="{{asset('storage/app/public/submissions/' . $submission->file_path)}}" style="text-align: center;">
                                Chấm điểm</i></a>
                            @else
                                <strong>Đã chấm điểm lúc <br>
                                {{ $submission->updated_at }}</strong>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<!--quay lại bài tập -->
    <a href="{{ route('all_exercises')}}" class="btn btn-warning mt-3">
        <i class="fa fa-rotate"></i> Quay lại danh sách bài tập
    </a>

<!-- Modal xem file -->
<div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFileModalLabel">Xem bài nộp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Đây là nơi chứa iframe để hiển thị file -->
                <iframe id="exerciseFileIframe" width="100%" height="500px" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal chấm điểm -->
<div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFileModalLabel">Xem bài nộp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Đây là nơi chứa iframe để hiển thị file -->
                <iframe id="exerciseFileIframe" width="100%" height="300px" style="border: none;"></iframe>
                @if(isset($submission))
                    <form action="{{ route('submission.grade', $submission->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="chapter_title" class="form-label">Thời gian nộp: </label>
                            <span>{{ $submission->created_at }}</span>
                        </div>
                        <input type="text" class="form-control" id="feedback" name="feedback"  placeholder="Đánh giá bài tập" style="border: 1px solid black;">
                        <label for="score" class="form-label"><strong>Điểm</strong></label>
                        <div class="d-flex" style="justify-content: space-around">
                            <input type="number" class="form-control" placeholder="Score" style="width: 500px !important; border: 1px solid black;" name="score" id="score" min="0" max="10" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Xác nhận điểm</button>
                            </div>
                        </div>
                    </form>
                @else
                    <p>Không có bài nộp nào để chấm điểm.</p>
                @endif
            </div>

        </div>
    </div>
</div>
<script>
    // Lắng nghe sự kiện khi modal được mở
    $('#viewFileModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // lấy button nhấn
        var filePath = button.data('file'); // lấy đường dẫn file từ data-file

        // Chèn đường dẫn file vào iframe
        var iframe = $(this).find('#exerciseFileIframe');
        iframe.attr('src', filePath); // Đặt nguồn cho iframe
    });
    $('#scoreModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // lấy button nhấn
        var filePath = button.data('file'); // lấy đường dẫn file từ data-file

        // Chèn đường dẫn file vào iframe
        var iframe = $(this).find('#exerciseFileIframe');
        iframe.attr('src', filePath); // Đặt nguồn cho iframe
    });
</script>
@endsection
