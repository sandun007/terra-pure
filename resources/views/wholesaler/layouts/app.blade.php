<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Wholesale Management System">
    <meta name="keywords" content="wholesale, inventory, management">
    <meta name="author" content="DreamX">
    <meta name="robots" content="noindex, nofollow">
    <title>@stack('title') - Wholesale Dashboard</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/theme/all.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/style.css')}}">

    <!-- Additional Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">

    <!-- Custom Wholesaler CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/wholesaler.css') }}">

    @stack('css')
</head>
<body>
<div id="global-loader" style="display: none;">
    <div class="whirly-loader"></div>
</div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    @include('wholesaler.component.header')
    @include('wholesaler.component.sidebar')

    <div class="page-wrapper">
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('asset/js/theme/jquery-3.7.1.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap Core JS -->
<script src="{{asset('asset/js/theme/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<!-- Custom JS -->
<script src="{{asset('asset/js/theme/script.js')}}" type="text/javascript"></script>
<!-- In the header/navigation area -->
<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="{{ route('wholesaler.shop.cart') }}" id="cartDropdown">
        <i class="fas fa-shopping-cart"></i>
        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle cart-count-badge">
            {{-- Cart count will be updated via JavaScript --}}
            0
        </span>
    </a>
</li>
@stack('script')
</body>
</html>
