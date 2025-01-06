<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('public/BackEnd/assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{asset('public/BackEnd/assets/img/favicon.png')}}">
  <title>
    Material Dashboard 2 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset('public/BackEnd/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('public/BackEnd/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('public/BackEnd/assets/css/material-dashboard.css?v=3.1.0')}}" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
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
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{asset('public/BackEnd/pages/dashboard.html')}}">
              Đăng ký
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{URL::to('/register')}}">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Đăng nhập
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{URL::to('/login')}}">
                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                    Đăng xuất
                  </a>
                </li>
              </ul>
              <ul class="navbar-nav d-lg-flex d-none">
                <li class="nav-item d-flex align-items-center">
                  <a class="btn btn-outline-primary btn-sm mb-0 me-2" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/product/material-dashboard" class="btn btn-sm mb-0 me-1 bg-gradient-dark">Tải về</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('{{ asset('public/BackEnd/assets/img/illustrations/illustration-signup.jpg') }}'); background-size: cover;"
>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Đăng ký</h4>
                  <p class="mb-0">Nhập tài khoản và mật khẩu để đăng ký </p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" action="{{ route('register') }}">
                    @csrf <!-- Thêm token bảo mật -->
                    <div class="input-group input-group-outline mb-3">
                        <label class="form-label"></label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên của bạn" required>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                        <label class="form-label"></label>
                        <input type="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                        <label class="form-label"></label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu >= 6 kí tự" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Đăng ký</button>
                    </div>
                </form>

                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Đã có tài khoản?
                    <a href="{{URL::to('/login')}}" class="text-primary text-gradient font-weight-bold">Đăng nhập</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="{{asset('public/BackEnd/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('public/BackEnd/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('public/BackEnd/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('public/BackEnd/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>

  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>