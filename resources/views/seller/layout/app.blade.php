<html lang="en" data-theme="light">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Seller Management System">
    <meta name="keywords" content="seller, management, dashboard">
    <meta name="author" content="DreamX">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Seller Dashboard</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/bootstrap.min.css')}}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/theme/all.min.css')}}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('asset/css/theme/style.css')}}">
    <!-- Additional Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --transition-speed: 0.3s;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #dbeafe;
            --secondary: #8b5cf6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --success: #10b981;
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --gray-dark: #475569;
            --sidebar-bg: #ffffff;
            --sidebar-text: #475569;
            --sidebar-active: #f1f5f9;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] {
            --sidebar-bg: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-active: #334155;
            --dark: #f1f5f9;
            --light: #0f172a;
            --gray: #94a3b8;
            --gray-light: #334155;
            --gray-dark: #cbd5e1;
            --border-color: #334155;
        }

        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            background: var(--light);
            position: relative;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.03);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            overflow: hidden;
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-logo img {
            height: 40px;
            width: 40px;
            border-radius: 10px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
        }

        .sidebar-toggle-btn {
            background: var(--sidebar-active);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray);
            transition: all 0.3s ease;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary);
            color: white;
            transform: rotate(180deg);
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 20px 15px;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu li a:hover {
            background: var(--sidebar-active);
            color: var(--primary);
            transform: translateX(5px);
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(37, 99, 235, 0.1));
            color: var(--primary);
            font-weight: 600;
        }

        .page-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-wrapper.collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .content {
            flex: 1;
            padding: 25px;
            margin-top: 20px;
            background: var(--light);
        }

        /* SweetAlert2 Customization */
        .swal2-popup {
            border-radius: 24px !important;
            padding: 2.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }

        .swal2-title {
            font-size: 2rem !important;
            font-weight: 800 !important;
        }

        .swal2-confirm, .swal2-cancel {
            border-radius: 14px !important;
            padding: 1rem 2.5rem !important;
            font-weight: 700 !important;
            transition: all 0.3s ease !important;
        }

        .swal2-confirm:hover, .swal2-cancel:hover {
            transform: translateY(-3px) !important;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1002;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .page-wrapper {
                margin-left: 0 !important;
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div id="global-loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading...</div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrapper">
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('seller.dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('asset/img/dx.png') }}" alt="Logo">
                <span class="logo-text">Seller Panel</span>
            </a>
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            @include('seller.component.sidebar')
        </div>
    </div>

    <div class="page-wrapper" id="pageWrapper">
        @include('seller.component.header')
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<script src="{{asset('asset/js/theme/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('asset/js/theme/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('asset/js/theme/script.js')}}"></script>

<script>
    // Global Alert System
    window.Alert = {
        success: (msg, title = 'Success!') => Swal.fire({
            icon: 'success',
            title,
            html: `<div style="font-size: 1.1rem;">${msg}</div>`,
            confirmButtonColor: '#10b981',
            timer: 3000,
            showClass: { popup: 'animate__animated animate__fadeInDown' }
        }),

        error: (msg, title = 'Error!') => Swal.fire({
            icon: 'error',
            title,
            html: `<div style="font-size: 1.1rem;">${msg}</div>`,
            confirmButtonColor: '#ef4444',
            showClass: { popup: 'animate__animated animate__shakeX' }
        }),

        warning: (msg, title = 'Warning!') => Swal.fire({
            icon: 'warning',
            title,
            html: `<div style="font-size: 1.1rem;">${msg}</div>`,
            confirmButtonColor: '#f59e0b'
        }),

        info: (msg, title = 'Info') => Swal.fire({
            icon: 'info',
            title,
            html: `<div style="font-size: 1.1rem;">${msg}</div>`,
            confirmButtonColor: '#06b6d4'
        }),

        confirm: (msg, title = 'Are you sure?') => Swal.fire({
            title,
            html: `<div style="font-size: 1.1rem;">${msg}</div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }),

        delete: (item = 'this item') => Swal.fire({
            title: 'Delete Confirmation',
            html: `
                <div style="font-size: 4rem; color: #ef4444; margin: 1rem 0; animation: shake 0.5s;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p style="font-size: 1.1rem;">Delete <strong style="color: #ef4444;">${item}</strong>?</p>
                <p style="color: #6b7280;">This action cannot be undone.</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }),

        toast: (msg, type = 'success') => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
            return Toast.fire({ icon: type, title: msg });
        },

        loading: (msg = 'Processing...') => Swal.fire({
            title: 'Please Wait',
            html: `
                <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;">
                </div>
                <h5 style="margin-top: 1rem;">${msg}</h5>
            `,
            showConfirmButton: false,
            allowOutsideClick: false
        })
    };

    $(document).ready(function() {
        setTimeout(() => $('#global-loader').fadeOut(500), 300);

        // Laravel Session Messages
        @if(session('success'))
        Alert.toast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
        Alert.toast('{{ session('error') }}', 'error');
        @endif

        @if(session('warning'))
        Alert.toast('{{ session('warning') }}', 'warning');
        @endif

        @if(session('info'))
        Alert.toast('{{ session('info') }}', 'info');
        @endif

        // Sidebar Toggle
        $('#sidebarToggle').click(function() {
            $('#sidebar').toggleClass('collapsed');
            $('#pageWrapper').toggleClass('collapsed');
        });

        // AJAX Setup
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    });
</script>

@stack('scripts')
</body>
</html>
