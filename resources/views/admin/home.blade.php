@extends('Admin.index')
@section('page-title', 'Dashboard')
@section('content')
@push('scripts')  <!-- đưa script ra ngoài -->
<script>
    // Lượt truy cập
    var ctxAccess = document.getElementById('chart-access').getContext('2d');
    new Chart(ctxAccess, {
        type: 'bar',
        data: {
            labels: ['Lượt truy cập'],
            datasets: [{
                label: 'Số lượt',
                data: [{{ $accessCount }}],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Tổng tiền thu được
    var ctxMoney = document.getElementById('chart-money').getContext('2d');
    new Chart(ctxMoney, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($dailyMoney->toArray())) !!}, // Ngày tháng từ database
            datasets: [{
                label: 'Tổng tiền (VNĐ)',
                data: {!! json_encode(array_values($dailyMoney->toArray())) !!}, // Dữ liệu thực tế
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

   // Học viên đăng ký
var ctxRegistered = document.getElementById('chart-registered').getContext('2d');
new Chart(ctxRegistered, {
    type: 'bar', 
    data: {
        labels: ['Học viên', 'Giảng viên', 'Quản trị viên'], // Các vai trò học viên
        datasets: [
            {
                label: 'Số lượng tài khoản',
                data: [{{ $studentsCount }}, {{ $teachersCount }}, {{ $adminsCount }}], // Truyền số lượng trực tiếp từ Controller
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],  // Màu sắc cho từng cột
                borderColor: ['#FF6384', '#36A2EB', '#FFCE56'],  // Màu viền cho cột
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true // Bắt đầu trục y từ 0
            }
        }
    }
});

</script>
@endpush
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
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">weekend</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Số tiền thu được</p>
                        <h4 class="mb-0">{{ number_format($totalMoney, 0, ',', '.') }} VNĐ</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">% </span>so với tuần trước</p>
                </div>
            </div>
        </div>
        
        <!-- Số người đăng ký tài khoản -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Số người đăng ký tài khoản</p>
                        <h4 class="mb-0">{{ $totalUsers }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">% </span>so với tháng trước</p>
                </div>
            </div>
        </div>

        <!-- Số khóa học được đăng ký -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">school</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Số khóa học học viên đăng ký</p>
                        <h4 class="mb-0">{{ $totalCourses }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">% </span>so với ngày hôm qua</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">weekend</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Số lần truy cập của học viên</p>
                <h4 class="mb-0"> {{ $accessCount }}</h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <p class="mb-0"><span class="text-success text-sm font-weight-bolder">% </span>so với ngày hôm qua</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-access" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0">Lượt Truy Cập</h6>
              <p class="text-sm">Số lượng học viên truy cập giao diện ngoài</p>
              <h3 class="font-weight-bold">{{ $accessCount }}</h3>
              <hr class="dark horizontal">
              <div class="d-flex">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm">Cập nhật lúc {{ now() }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-money" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0">Tổng Tiền Thu Được</h6>
              <p class="text-sm">Số tiền học viên nạp vào hệ thống</p>
              <h3 class="font-weight-bold">{{ number_format($totalMoney, 0, ',', '.') }} VNĐ</h3>
              <hr class="dark horizontal">
              <div class="d-flex">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm">Cập nhật lúc {{ now() }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 mt-4 mb-3">
          <div class="card z-index-2">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                  <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                      <div class="chart">
                          <canvas id="chart-registered" class="chart-canvas" height="170" style="max-width: 100%;"></canvas>
                      </div>
                  </div>
              </div>
              <div class="card-body">
                  <h6 class="mb-0">Học Viên Đăng Ký</h6>
                  <p class="text-sm">Tổng số học viên đăng ký vào hệ thống</p>
                  <h3 class="font-weight-bold">{{ $totalUsers }}</h3>
                  <hr class="dark horizontal">
                  <div class="d-flex">
                      <i class="material-icons text-sm my-auto me-1">schedule</i>
                      <p class="mb-0 text-sm">Cập nhật lúc {{ now() }}</p>
                  </div>
              </div>
          </div>
        </div>
      </div>  
      
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>

@endsection
