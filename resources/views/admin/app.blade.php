<!doctype html>
<html lang="en">
@include('admin.component.head')
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
          content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>
        @stack('title')
    </title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">


    <script src="{{ asset('asset/js/theme/theme-script.js')}}" type="text/javascript"></script>
    <script src="{{ asset('asset/js/theme/theme-9c065fc6.js')}}" type="text/javascript"></script>
    <script src="{{ asset('asset/js/theme/apexcharts.common-4fae8482.js')}}" type="text/javascript"></script>

    <!-- Favicon -->
    <!-- Add this in the <head> section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/bootstrap.min.css')}}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/bootstrap-datetimepicker.min.css')}}">

    <!-- animation CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/animate.css')}}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/select2.min.css')}}select2.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/theme/all.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/style.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/theme/theme-ecf0ae99.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">


    <!-- pages CSS -->
    <link href="{{ asset('asset/css/pages/dashbord.css') }}" rel="stylesheet">


    <!-- Header Styles -->
    <link rel="stylesheet" href="{{ asset('asset/css/component/header.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/component/sliderbar.css') }}">


    @stack('css')
</head>
<body>
<div id="global-loader" style="display: none;">
    <div class="whirly-loader"></div>
</div>
<!-- Main Wrapper -->
<div class="main-wrapper">
    @include('admin.component.header')
    @include('admin.component.sidebar')
    <div class="page-wrapper" style="min-height: 927px;">
        <div class="content">
            @yield('content')
        </div>
    </div>
    <div class="sidebar-settings nav-toggle" id="layoutDiv">
        <div class="sidebar-content sticky-sidebar-one">
            <div class="sidebar-header">
                <div class="sidebar-theme-title">
                    <h5>Theme Customizer</h5>
                    <p>Customize &amp; Preview in Real Time</p>
                </div>
                <div class="close-sidebar-icon d-flex">
                    <a class="sidebar-refresh me-2" onclick="resetData()">⟳</a>
                    <a class="sidebar-close" href="#">X</a>
                </div>
            </div>
            <div class="sidebar-body p-0">
                <div class="theme-mode mb-0">
                    <div class="theme-body-main">
                        <div class="theme-head">
                            <h6>Theme Mode</h6>
                            <p>Enjoy Dark &amp; Light modes.</p>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 ">
                                <div class="layout-wrap">
                                    <div class="d-flex align-items-center">
                                        <div class="status-toggle d-flex align-items-center me-2">
                                            <input type="radio" name="themes" id="lighttheme" class="check"
                                                   value="light_mode" checked="checked">
                                            <label for="lighttheme" class="checktoggles">
                                                <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-01.webp"
                                                     alt="">
                                                <span class="theme-name">Light Mode</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="layout-wrap">
                                    <div class="d-flex align-items-center">
                                        <div class="status-toggle d-flex align-items-center me-2">
                                            <input type="radio" name="themes" id="darktheme" class="check"
                                                   value="dark_mode">
                                            <label for="darktheme" class="checktoggles">
                                                <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-02.webp"
                                                     alt="">
                                                <span class="theme-name">Dark Mode</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="theme-mode border-0">
                            <div class="theme-head">
                                <h6>Direction</h6>
                                <p>Select the direction for your app.</p>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="direction" id="ltr" class="check direction"
                                                       value="ltr" checked="checked">
                                                <label for="ltr" class="checktoggles">
                                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/index.html"><img
                                                            src="Dreams%20Pos%20Admin%20Template_files/theme-img-01.webp"
                                                            alt=""></a>
                                                    <span class="theme-name">LTR</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="direction" id="rtl" class="check direction"
                                                       value="rtl">
                                                <label for="rtl" class="checktoggles">
                                                    <a href="https://dreamspos.dreamstechnologies.com/html/template-rtl/index.html"
                                                       target="_blank"><img
                                                            src="Dreams%20Pos%20Admin%20Template_files/theme-img-03.webp"
                                                            alt=""></a>
                                                    <span class="theme-name">RTL</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-mode border-0 mb-0">
                            <div class="theme-head">
                                <h6>Layout Mode</h6>
                                <p>Select the primary layout style for your app.</p>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="layout" id="default_layout"
                                                       class="check layout-mode" value="default" checked="checked">
                                                <label for="default_layout" class="checktoggles">
                                                    <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-01.webp"
                                                         alt="">
                                                    <span class="theme-name">Default</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="layout" id="box_layout"
                                                       class="check layout-mode" value="box">
                                                <label for="box_layout" class="checktoggles">
                                                    <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-07.webp"
                                                         alt="">
                                                    <span class="theme-name">Box</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="layout" id="collapse_layout"
                                                       class="check layout-mode" value="collapsed">
                                                <label for="collapse_layout" class="checktoggles">
                                                    <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-05.webp"
                                                         alt="">
                                                    <span class="theme-name">Collapsed</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="layout" id="horizontal_layout"
                                                       class="check layout-mode" value="horizontal">
                                                <label for="horizontal_layout" class="checktoggles">
                                                    <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-06.webp"
                                                         alt="">
                                                    <span class="theme-name">Horizontal</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="layout" id="modern_layout"
                                                       class="check layout-mode" value="modern">
                                                <label for="modern_layout" class="checktoggles">
                                                    <img src="Dreams%20Pos%20Admin%20Template_files/theme-img-04.webp"
                                                         alt="">
                                                    <span class="theme-name">Modern</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-mode">
                            <div class="theme-head">
                                <h6>Navigation Colors</h6>
                                <p>Setup the color for the Navigation</p>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="nav_color" id="light_color"
                                                       class="check nav-color" value="light" checked="checked">
                                                <label for="light_color" class="checktoggles">
                                                    <span class="theme-name">Light</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="nav_color" id="grey_color"
                                                       class="check nav-color" value="grey">
                                                <label for="grey_color" class="checktoggles">
                                                    <span class="theme-name">Grey</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 ere">
                                    <div class="layout-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="status-toggle d-flex align-items-center me-2">
                                                <input type="radio" name="nav_color" id="dark_color"
                                                       class="check nav-color" value="dark">
                                                <label for="dark_color" class="checktoggles">
                                                    <span class="theme-name">Dark</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-footer">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="footer-preview-btn">
                                <a href="#" class="btn btn-secondary w-100" id="resetbutton">Reset</a>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="footer-reset-btn">
                                <a href="#" class="btn btn-primary w-100">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sidebar-overlay"></div>
<div class="sidebar-filter"></div>
<!-- jQuery -->
<script src="{{asset('asset/js/theme/jquery-3.7.1.min.js')}}" type="text/javascript"></script>

<!-- Feather Icon JS -->
<script src="{{asset('asset/js/theme/feather.min.js')}}" type="text/javascript"></script>

<!-- Slimscroll JS -->
<script src="{{asset('asset/js/theme/jquery.slimscroll.min.js')}}" type="text/javascript"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('asset/js/theme/bootstrap.bundle.min.js')}}" type="text/javascript"></script>

<!-- Chart JS -->
<script src="{{asset('asset/js/theme/apexcharts.min.js')}}" type="text/javascript"></script>
<script src="{{asset('asset/js/theme/chart-data.js')}}" type="text/javascript"></script>

<!-- Sweetalert 2 -->
<script src="{{asset('asset/js/theme/sweetalert2.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('asset/js/theme/sweetalerts.min.js')}}" type="text/javascript"></script>

<!-- Custom JS -->

<script src="{{asset('asset/js/theme/script.js')}}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script defer="defer" src="{{asset('asset/vcd15cbe7772f49c399c6a5babf22c1241717689176015')}}"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon="{&quot;rayId&quot;:&quot;915e573e4f14513a&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}},&quot;version&quot;:&quot;2025.1.0&quot;,&quot;token&quot;:&quot;3ca157e612a14eccbb30cf6db6691c29&quot;}"
        crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script defer="defer" src="{{asset('asset/vcd15cbe7772f49c399c6a5babf22c1241717689176015')}}"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon="{&quot;rayId&quot;:&quot;915e573e4f14513a&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}},&quot;version&quot;:&quot;2025.1.0&quot;,&quot;token&quot;:&quot;3ca157e612a14eccbb30cf6db6691c29&quot;}"
        crossorigin="anonymous"></script>

<!-- comp scripts -->
<script src="{{ asset('asset/js/component/sidebar-toggle.js') }}"></script>
<script src="{{ asset('asset/js/component/Dropdown.js') }}"></script>

@stack('script')
</body>
</html>
