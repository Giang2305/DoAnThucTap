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
@if(session('alreadyRegisteredCourses'))
    <div class="alert alert-warning">
        <strong>Bạn đã đăng ký các khóa học sau:</strong>
        <ul>
            @foreach(session('alreadyRegisteredCourses') as $courseName)
                <li>Khoá học {{ $courseName }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-5">
    <h2>Giỏ hàng của bạn</h2>
    @if($cartItems->isEmpty())
        <p>Giỏ hàng trống!</p>
    @else
        <table class="table ">
            <thead>
                <tr>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ảnh</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên khóa học</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giảng viên</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Giá tiền</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td class="align-middle text-center"><img style="border-radius: 2rem; width: 200px; height: 120px" src="{{asset('public/images/' . $item->course->Image) }}" alt="Thư viện chuẩn C++" class="img-fluid" ></td>
                        <td class="align-middle text-center">Khoá học {{ $item->course->Name }}</td>
                        <td class="align-middle text-center">{{ $item->course->Teacher }}</td>
                        <td class="align-middle text-center">{{ $item->course->Price }} </td>
                        <td class="align-middle text-center">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="border-radius: 2rem;" onclick="return confirm('Bạn đã xác định xoá khoá học này khỏi giỏ hàng chưa?');">Xóa khỏi giỏ hàng</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                        <td class="align-middle text-center"></td>
                        <td class="align-middle text-center"></td>
                        <td class="align-middle text-center"></td>
                        <td class="align-middle text-center" style="font-size: 20px;"> <strong>Tổng tiền:</strong> {{ number_format($totalPrice, 0, ',', '.') }}đ</td>
                        <td class="align-middle text-center">
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success" style="border-radius: 2rem;" onclick="return confirm('Xác nhận thanh toán tất cả khoá học có trong giỏ hàng với số tiền {{ number_format($totalPrice, 0, ',', '.') }}đ?');">Thanh toán tất cả</button>
                            </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    @endif
</div>
@if(session('showModal'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        });
    </script>
@endif

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Thông báo thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('error') }}
                <p>Vui lòng chọn phương thức thanh toán:</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection
