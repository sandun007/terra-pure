<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Seller Management System">
    <meta name="keywords" content="seller, management, dashboard, e-commerce">
    <meta name="author" content="DreamX">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@stack('title', 'Seller Dashboard') - Seller Panel</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/theme/all.min.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/style.css') }}">
    <!-- Additional Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            --shadow-xl: 0 20px 40px -5px rgba(0, 0, 0, 0.1);
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
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        }

        /* Global Loader */
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

        #global-loader.hidden {
            opacity: 0;
            pointer-events: none;
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

        .loader-text {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-align: center;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Main Wrapper */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            background: var(--light);
            position: relative;
            transition: all var(--transition-speed) ease;
        }

        /* Sidebar Styles */
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

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90px;
            background: var(--sidebar-bg);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 0;
        }

        .sidebar-logo img {
            height: 40px;
            width: 40px;
            object-fit: contain;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            margin: 0;
            overflow: hidden;
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
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary);
            color: white;
            transform: rotate(180deg);
        }

        .sidebar.collapsed .sidebar-toggle-btn {
            transform: rotate(180deg);
            margin: 0 auto;
        }

        .sidebar.collapsed .sidebar-toggle-btn:hover {
            transform: rotate(0deg);
        }

        /* Sidebar Menu */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 20px 15px;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-light) transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: var(--gray-light);
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: var(--gray);
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-section {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray);
            font-weight: 600;
            margin: 25px 0 10px 15px;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-section {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .sidebar-menu li {
            position: relative;
            margin-bottom: 5px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 500;
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
            border-left: 4px solid var(--primary);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: inherit;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed li a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed li a {
            justify-content: center;
            padding: 12px;
        }

        .sidebar.collapsed .menu-icon {
            margin: 0;
        }

        /* Badge */
        .menu-badge {
            background: var(--danger);
            color: white;
            font-size: 11px;
            font-weight: 600;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            margin-left: auto;
        }

        .sidebar.collapsed .menu-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            margin: 0;
        }

        /* Tooltips for collapsed sidebar */
        .sidebar.collapsed li a::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1002;
            pointer-events: none;
            box-shadow: var(--card-shadow-hover);
        }

        .sidebar.collapsed li a:hover::after {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 15px);
        }

        /* Page Wrapper */
        .page-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
        }

        .page-wrapper.collapsed {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 25px;
            background: var(--light);
            min-height: calc(100vh - var(--header-height));
        }

        /* Header */
        .app-header {
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            flex-shrink: 0;
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* SweetAlert2 Customization */
        .swal2-popup {
            border-radius: 24px !important;
            padding: 2.5rem !important;
            box-shadow: var(--shadow-xl) !important;
        }

        .swal2-title {
            font-size: 2rem !important;
            font-weight: 800 !important;
            color: var(--dark) !important;
        }

        .swal2-html-container {
            color: var(--gray-dark) !important;
            font-size: 1.1rem !important;
        }

        .swal2-confirm, .swal2-cancel {
            border-radius: 14px !important;
            padding: 1rem 2.5rem !important;
            font-weight: 700 !important;
            transition: all 0.3s ease !important;
        }

        .swal2-confirm:hover, .swal2-cancel:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: var(--shadow-xl);
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            border-left: 4px solid var(--primary);
            max-width: 350px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
        }

        .notification-success { border-left-color: var(--success); }
        .notification-warning { border-left-color: var(--warning); }
        .notification-error { border-left-color: var(--danger); }
        .notification-info { border-left-color: var(--info); }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Back to Top - FIXED: Added visible class for proper flex display */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.visible {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Body menu open state */
        body.menu-open {
            overflow: hidden;
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 5px 0 30px rgba(0, 0, 0, 0.1);
                z-index: 1002;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .page-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .mobile-menu-toggle {
                display: flex;
            }
            .sidebar-header {
                padding: 20px;
            }
            .sidebar-toggle-btn {
                display: none;
            }
            .content {
                padding: 20px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                width: 100%;
                max-width: 280px;
            }
            .content {
                padding: 15px;
            }
            .app-header {
                padding: 15px 20px;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 12px;
            }
            .sidebar-menu {
                padding: 15px 10px;
            }
            .sidebar-menu li a {
                padding: 10px 12px;
                font-size: 13px;
            }
            .swal2-popup {
                padding: 1.5rem !important;
                margin: 0 10px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray);
        }

        /* Selection */
        ::selection {
            background-color: var(--primary-light);
            color: var(--dark);
        }

        /* Focus Styles */
        *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        *:focus:not(:focus-visible) {
            outline: none;
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
    @stack('css')
</head>
<body>
<!-- Global Loader -->
<div id="global-loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading Seller Dashboard...</div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" aria-label="Main navigation">
        <div class="sidebar-header">
            <a href="{{ route('seller.dashboard') }}" class="sidebar-logo" aria-label="Go to dashboard">
                <img src="{{ asset('asset/img/dx.png') }}" alt="DreamX Logo" loading="lazy">
                <span class="logo-text">Seller Panel</span>
            </a>
            <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <nav class="sidebar-menu" aria-label="Primary navigation">
            @include('seller.partials.sidebar')
        </nav>
    </aside>

    <!-- Page Wrapper -->
    <div class="page-wrapper" id="pageWrapper">
        <!-- Header -->
        <header class="app-header">
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-content">
                @if(isset($header))
                    {!! $header !!}
                @else
                    <h1 class="page-title h4 mb-0">@yield('page-title', 'Dashboard')</h1>
                @endif
            </div>
            <div class="header-actions">
                @include('seller.component.header')
            </div>
        </header>

        <!-- Main Content -->
        <main class="content" id="mainContent">
            @yield('content')
        </main>

        <!-- Footer (Optional) -->
        <footer class="app-footer d-none">
            <div class="container-fluid py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span class="text-muted">© {{ date('Y') }} DreamX. All rights reserved.</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted">v1.0.0</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- JavaScript Libraries -->
<script src="{{ asset('asset/js/theme/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('asset/js/theme/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('asset/js/theme/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // IIFE to prevent global scope pollution and ensure single initialization
    (function() {
        'use strict';

        // Prevent multiple initializations
        if (window.sellerAppInitialized) {
            return;
        }
        window.sellerAppInitialized = true;

        // State management object
        const AppState = {
            isMobile: window.innerWidth <= 991,
            sidebarState: localStorage.getItem('sellerSidebarState') || 'expanded',
            isMenuOpen: false,
            resizeTimer: null
        };

        // Cache DOM elements
        const DOM = {
            loader: null,
            sidebar: null,
            pageWrapper: null,
            sidebarOverlay: null,
            mobileMenuToggle: null,
            sidebarToggle: null,
            backToTop: null,
            body: null
        };

        // Initialize when DOM is ready
        $(document).ready(function() {
            cacheElements();
            initializeApp();
        });

        function cacheElements() {
            DOM.loader = $('#global-loader');
            DOM.sidebar = $('#sidebar');
            DOM.pageWrapper = $('#pageWrapper');
            DOM.sidebarOverlay = $('#sidebarOverlay');
            DOM.mobileMenuToggle = $('#mobileMenuToggle');
            DOM.sidebarToggle = $('#sidebarToggle');
            DOM.backToTop = $('#backToTop');
            DOM.body = $('body');
        }

        function initializeApp() {
            hideLoader();
            initializeSidebar();
            bindEvents();
            addTooltips();
            highlightActiveMenuItem();
            setupAjax();
        }

        function hideLoader() {
            requestAnimationFrame(function() {
                setTimeout(function() {
                    DOM.loader.addClass('hidden');
                    setTimeout(function() {
                        DOM.loader.remove();
                    }, 500);
                }, 300);
            });
        }

        function initializeSidebar() {
            AppState.isMobile = window.innerWidth <= 991;

            if (AppState.isMobile) {
                DOM.sidebar.removeClass('collapsed');
                DOM.pageWrapper.removeClass('collapsed');
            } else {
                if (AppState.sidebarState === 'collapsed') {
                    DOM.sidebar.addClass('collapsed');
                    DOM.pageWrapper.addClass('collapsed');
                }
            }
        }

        function bindEvents() {
            DOM.sidebarToggle.off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (window.innerWidth > 991) {
                    toggleSidebar();
                }
            });

            DOM.mobileMenuToggle.off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileMenu();
            });

            DOM.sidebarOverlay.off('click').on('click', function(e) {
                e.preventDefault();
                closeMobileMenu();
            });

            $(document).off('click.sidebarMenu').on('click.sidebarMenu', '.sidebar-menu a', function(e) {
                if (window.innerWidth <= 991) {
                    e.stopPropagation();
                    closeMobileMenu();
                }
            });

            $(window).off('resize.sellerApp').on('resize.sellerApp', function() {
                clearTimeout(AppState.resizeTimer);
                AppState.resizeTimer = setTimeout(handleResize, 250);
            });

            $(document).off('keydown.sellerApp').on('keydown.sellerApp', handleKeyboardShortcuts);

            $(window).off('scroll.header').on('scroll.header', function() {
                const header = $('.app-header');
                if ($(this).scrollTop() > 50) {
                    header.addClass('scrolled');
                } else {
                    header.removeClass('scrolled');
                }
            });

            $(window).off('scroll.backToTop').on('scroll.backToTop', function() {
                if ($(this).scrollTop() > 300) {
                    DOM.backToTop.addClass('visible');
                } else {
                    DOM.backToTop.removeClass('visible');
                }
            });

            DOM.backToTop.off('click').on('click', function() {
                $('html, body').animate({ scrollTop: 0 }, 300);
                return false;
            });

            $(document).off('click.menuActive').on('click.menuActive', '.sidebar-menu a', function(e) {
                if (!$(this).hasClass('logout-link')) {
                    requestAnimationFrame(highlightActiveMenuItem);
                }
            });
        }

        function toggleSidebar() {
            DOM.sidebar.toggleClass('collapsed');
            DOM.pageWrapper.toggleClass('collapsed');

            const isCollapsed = DOM.sidebar.hasClass('collapsed');
            AppState.sidebarState = isCollapsed ? 'collapsed' : 'expanded';
            localStorage.setItem('sellerSidebarState', AppState.sidebarState);

            window.dispatchEvent(new CustomEvent('sidebarToggle', {
                detail: { collapsed: isCollapsed }
            }));
        }

        function toggleMobileMenu() {
            AppState.isMenuOpen = !AppState.isMenuOpen;

            DOM.sidebar.toggleClass('active');
            DOM.sidebarOverlay.toggleClass('active');

            if (AppState.isMenuOpen) {
                DOM.body.addClass('menu-open');
            } else {
                DOM.body.removeClass('menu-open');
            }
        }

        function closeMobileMenu() {
            AppState.isMenuOpen = false;
            DOM.sidebar.removeClass('active');
            DOM.sidebarOverlay.removeClass('active');
            DOM.body.removeClass('menu-open');
        }

        function handleResize() {
            const wasMobile = AppState.isMobile;
            const nowMobile = window.innerWidth <= 991;

            AppState.isMobile = nowMobile;

            if (nowMobile && !wasMobile) {
                DOM.sidebar.removeClass('collapsed');
                DOM.pageWrapper.removeClass('collapsed');
                closeMobileMenu();
            }

            if (!nowMobile && wasMobile) {
                closeMobileMenu();
                if (AppState.sidebarState === 'collapsed') {
                    DOM.sidebar.addClass('collapsed');
                    DOM.pageWrapper.addClass('collapsed');
                }
            }
        }

        function addTooltips() {
            $('.sidebar-menu a').each(function() {
                const text = $(this).find('span').text();
                if (text) {
                    $(this).attr('data-title', text);
                }
            });
        }

        function highlightActiveMenuItem() {
            const currentPath = window.location.pathname;
            const currentUrl = window.location.href;

            $('.sidebar-menu a').removeClass('active');

            let bestMatch = null;
            let bestMatchLength = 0;

            $('.sidebar-menu a').each(function() {
                const href = $(this).attr('href');
                if (!href || href === '#') return;

                if (currentPath === href || currentUrl === href) {
                    bestMatch = $(this);
                    bestMatchLength = Infinity;
                    return false;
                }

                if (href !== '/' && currentPath.startsWith(href)) {
                    if (href.length > bestMatchLength) {
                        bestMatch = $(this);
                        bestMatchLength = href.length;
                    }
                }

                if (currentUrl.includes(href) && href.includes('?')) {
                    if (href.length > bestMatchLength) {
                        bestMatch = $(this);
                        bestMatchLength = href.length;
                    }
                }
            });

            if (bestMatch) {
                bestMatch.addClass('active');
            }
        }

        function handleKeyboardShortcuts(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                if (window.innerWidth > 991) {
                    e.preventDefault();
                    toggleSidebar();
                }
            }

            if (e.key === 'Escape' && AppState.isMenuOpen) {
                e.preventDefault();
                closeMobileMenu();
            }

            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = $('.search-input');
                if (searchInput.length) {
                    searchInput.focus();
                }
            }
        }

        function setupAjax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                const errorMessages = {
                    419: 'Session expired. Please refresh the page.',
                    403: 'You do not have permission to perform this action.',
                    404: 'Resource not found.',
                    500: 'Server error. Please try again later.'
                };

                if (errorMessages[jqxhr.status]) {
                    Alert.toast(errorMessages[jqxhr.status], 'error');
                }
            });
        }

        window.sellerApp = {
            toggleSidebar: toggleSidebar,
            closeMobileMenu: closeMobileMenu,
            highlightActiveMenuItem: highlightActiveMenuItem,
            getState: function() {
                return { ...AppState };
            },
            showNotification: function(message, type = 'info') {
                const iconMap = {
                    success: 'check-circle',
                    warning: 'exclamation-triangle',
                    error: 'times-circle',
                    info: 'info-circle'
                };

                const notification = $('<div>')
                    .addClass(`notification notification-${type}`)
                    .html(`
                        <div class="notification-content">
                            <i class="fas fa-${iconMap[type] || 'info-circle'}"></i>
                            <span>${message}</span>
                        </div>
                    `);

                $('body').append(notification);

                setTimeout(function() {
                    notification.css('animation', 'slideOutRight 0.3s ease forwards');
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }, 5000);
            }
        };
    })();

    window.Alert = {
        success: function(msg, title = 'Success!') {
            return Swal.fire({
                icon: 'success',
                title: title,
                html: '<div style="font-size: 1.1rem;">' + msg + '</div>',
                confirmButtonColor: '#10b981',
                timer: 3000,
                showClass: { popup: 'animate__animated animate__fadeInDown' }
            });
        },

        error: function(msg, title = 'Error!') {
            return Swal.fire({
                icon: 'error',
                title: title,
                html: '<div style="font-size: 1.1rem;">' + msg + '</div>',
                confirmButtonColor: '#ef4444',
                showClass: { popup: 'animate__animated animate__shakeX' }
            });
        },

        warning: function(msg, title = 'Warning!') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                html: '<div style="font-size: 1.1rem;">' + msg + '</div>',
                confirmButtonColor: '#f59e0b'
            });
        },

        info: function(msg, title = 'Info') {
            return Swal.fire({
                icon: 'info',
                title: title,
                html: '<div style="font-size: 1.1rem;">' + msg + '</div>',
                confirmButtonColor: '#06b6d4'
            });
        },

        confirm: function(msg, title = 'Are you sure?') {
            return Swal.fire({
                title: title,
                html: '<div style="font-size: 1.1rem;">' + msg + '</div>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
        },

        delete: function(item = 'this item') {
            return Swal.fire({
                title: 'Delete Confirmation',
                html: '\
                    <div style="font-size: 4rem; color: #ef4444; margin: 1rem 0;">\
                        <i class="fas fa-exclamation-triangle"></i>\
                    </div>\
                    <p style="font-size: 1.1rem;">Delete <strong style="color: #ef4444;">' + item + '</strong>?</p>\
                    <p style="color: #6b7280;">This action cannot be undone.</p>\
                ',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash me-2"></i>Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
        },

        toast: function(msg, type = 'success') {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: 'var(--sidebar-bg)',
                color: 'var(--dark)',
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                }
            });
            return Toast.fire({ icon: type, title: msg });
        },

        loading: function(msg = 'Processing...') {
            return Swal.fire({
                title: 'Please Wait',
                html: '\
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;">\
                    </div>\
                    <h5 style="margin-top: 1rem;">' + msg + '</h5>\
                ',
                showConfirmButton: false,
                allowOutsideClick: false,
                background: 'var(--sidebar-bg)',
                color: 'var(--dark)'
            });
        }
    };

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

    window.addEventListener('online', function() {
        Alert.toast('You are back online', 'success');
    });

    window.addEventListener('offline', function() {
        Alert.toast('You are offline', 'warning');
    });
</script>

@stack('scripts')
@stack('script')
</body>
</html>
