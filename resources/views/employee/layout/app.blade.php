<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="description" content="Employee Management System">
    <meta name="keywords" content="employee, management, dashboard">
    <meta name="author" content="DreamX">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@stack('title', 'Employee Dashboard') - Employee Dashboard</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/theme/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/theme/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">

    <!-- Extra -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 82px;
            --header-height: 76px;
            --mobile-header-height: 64px;
            --transition-speed: 0.3s;
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --success: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --gray-dark: #475569;
            --sidebar-bg: #ffffff;
            --sidebar-text: #475569;
            --sidebar-active: #f1f5f9;
            --border-color: #e2e8f0;
            --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.10);
            --body-bg: #f8fafc;
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
            --body-bg: #0f172a;
        }

        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            overflow-x: hidden;
            background: var(--body-bg);
        }

        body.menu-open {
            overflow: hidden;
        }

        /* Global Loader */
        #global-loader {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            opacity: 1;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-spinner {
            width: 56px;
            height: 56px;
            border: 4px solid rgba(255, 255, 255, 0.22);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
            margin-bottom: 18px;
        }

        .loader-text {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-align: center;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Layout */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            background: var(--body-bg);
            position: relative;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1100;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: width var(--transition-speed) ease, transform var(--transition-speed) ease;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            min-height: var(--header-height);
            padding: 18px 18px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            background: var(--sidebar-bg);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            min-width: 0;
            max-width: 100%;
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            flex-shrink: 0;
            border-radius: 10px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.25s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            margin: 0;
            overflow: hidden;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px 12px 20px;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-light) transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: var(--gray-light);
            border-radius: 10px;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .submenu-hdr {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gray);
            font-weight: 700;
            margin: 18px 10px 10px;
            transition: all 0.25s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .submenu-hdr {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 12px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 6px;
            transition: all 0.25s ease;
            position: relative;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            overflow: hidden;
        }

        .sidebar-menu li a:hover {
            background: var(--sidebar-active);
            color: var(--primary);
            transform: translateX(4px);
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.08));
            color: var(--primary);
        }

        .sidebar-menu li a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 4px;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            border-radius: 0 10px 10px 0;
        }

        .sidebar-menu li a svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .sidebar-menu li a span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .sidebar-menu li a {
            justify-content: center;
            padding: 12px;
        }

        .sidebar.collapsed .sidebar-menu li a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Tooltip when collapsed */
        .sidebar.collapsed li a::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 14px);
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
            transition: all 0.2s ease;
            z-index: 1200;
            pointer-events: none;
            box-shadow: var(--card-shadow-hover);
        }

        .sidebar.collapsed li a:hover::after {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-menu .badge {
            margin-left: auto;
            font-size: 11px !important;
            font-weight: 700;
            border-radius: 999px;
            min-width: 22px;
            padding: 6px 8px;
        }

        .sidebar.collapsed .sidebar-menu .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            margin-left: 0;
        }

        /* Page Wrapper */
        .page-wrapper {
            flex: 1;
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition-speed) ease, width var(--transition-speed) ease;
        }

        .page-wrapper.collapsed {
            width: calc(100% - var(--sidebar-collapsed-width));
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(5px);
            z-index: 1090;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed) ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Content */
        .content {
            flex: 1;
            padding: calc(var(--header-height) + 24px) 24px 24px;
            background: var(--body-bg);
            min-height: 100vh;
        }

        /* Footer */
        .app-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background: var(--sidebar-bg);
        }

        /* Mobile & Tablet */
        @media (max-width: 991.98px) {
            .sidebar {
                width: min(86vw, 300px);
                transform: translateX(-110%);
                box-shadow: 8px 0 40px rgba(0, 0, 0, 0.16);
                z-index: 1100;
            }

            .sidebar.active {
                transform: translateX(0) !important;
            }

            .sidebar.collapsed {
                width: min(86vw, 300px);
            }

            .sidebar.collapsed .logo-text,
            .sidebar.collapsed .submenu-hdr,
            .sidebar.collapsed .sidebar-menu li a span {
                opacity: 1;
                width: auto;
                visibility: visible;
            }

            .sidebar.collapsed .sidebar-menu li a {
                justify-content: flex-start;
                padding: 12px 14px;
            }

            .sidebar.collapsed .sidebar-menu li a::after {
                display: none;
            }

            .page-wrapper,
            .page-wrapper.collapsed {
                width: 100%;
                margin-left: 0;
            }

            .sidebar-header {
                min-height: var(--mobile-header-height);
            }

            .content {
                padding: calc(var(--mobile-header-height) + 16px) 16px 16px;
            }
        }

        @media (max-width: 767.98px) {
            .content {
                padding: calc(var(--mobile-header-height) + 14px) 14px 14px;
            }

            .sidebar-menu {
                padding: 14px 10px 16px;
            }

            .sidebar-menu li a {
                min-height: 44px;
                padding: 10px 12px;
                font-size: 13px;
            }

            .sidebar-logo img {
                width: 34px;
                height: 34px;
            }

            .logo-text {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 72px 12px 12px;
            }

            .sidebar {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Focus */
        *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        *:focus:not(:focus-visible) {
            outline: none;
        }

        /* Scrollbars */
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

        ::selection {
            background-color: var(--primary-light);
            color: var(--dark);
        }

        @media print {
            .sidebar,
            .sidebar-overlay,
            .header-toggle-btn {
                display: none !important;
            }

            .page-wrapper,
            .page-wrapper.collapsed {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .content {
                padding: 0 !important;
                background: white !important;
            }
        }
    </style>

    @stack('css')
</head>
<body>
<div id="global-loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading Dashboard...</div>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" aria-label="Sidebar Navigation">
        <div class="sidebar-header">
            <a href="{{ route('employee.dashboard') }}" class="sidebar-logo" aria-label="Employee Dashboard">
                <img src="{{ asset('asset/img/dx.png') }}" alt="DreamX Logo">
                <span class="logo-text">Employee Panel</span>
            </a>
        </div>

        <nav class="sidebar-menu">
            @include('employee.component.sidebar')
        </nav>
    </aside>

    <!-- Page Wrapper -->
    <div class="page-wrapper" id="pageWrapper">
        @include('employee.component.header')

        <main class="content" id="mainContent">
            @yield('content')
        </main>

        <footer class="app-footer d-none">
            <div class="container-fluid">
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

<!-- jQuery -->
<script src="{{ asset('asset/js/theme/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('asset/js/theme/bootstrap.bundle.min.js') }}"></script>
<!-- Optional Theme Script -->
<script src="{{ asset('asset/js/theme/script.js') }}"></script>

<script>
    $(document).ready(function () {
        const $loader = $('#global-loader');
        const $sidebar = $('#sidebar');
        const $pageWrapper = $('#pageWrapper');
        const $overlay = $('#sidebarOverlay');
        const $toggleBtn = $('#headerSidebarToggle');

        function isMobileView() {
            return window.innerWidth <= 991.98;
        }

        function closeMobileSidebar() {
            $sidebar.removeClass('active');
            $overlay.removeClass('active');
            $('body').removeClass('menu-open').css('overflow', '');
        }

        function openMobileSidebar() {
            $sidebar.addClass('active');
            $overlay.addClass('active');
            $('body').addClass('menu-open').css('overflow', 'hidden');
        }

        function toggleDesktopSidebar() {
            $sidebar.toggleClass('collapsed');
            $pageWrapper.toggleClass('collapsed');

            const isCollapsed = $sidebar.hasClass('collapsed');
            localStorage.setItem('employeeSidebarState', isCollapsed ? 'collapsed' : 'expanded');
        }

        function applyDesktopState() {
            closeMobileSidebar();

            const savedState = localStorage.getItem('employeeSidebarState') || 'expanded';

            if (savedState === 'collapsed') {
                $sidebar.addClass('collapsed');
                $pageWrapper.addClass('collapsed');
            } else {
                $sidebar.removeClass('collapsed');
                $pageWrapper.removeClass('collapsed');
            }
        }

        function applyMobileState() {
            $sidebar.removeClass('collapsed');
            $pageWrapper.removeClass('collapsed');
            closeMobileSidebar();
        }

        function syncLayout() {
            if (isMobileView()) {
                applyMobileState();
            } else {
                applyDesktopState();
            }
        }

        function handleToggle() {
            if (isMobileView()) {
                if ($sidebar.hasClass('active')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                toggleDesktopSidebar();
            }
        }

        function highlightActiveMenuItem() {
            const currentPath = window.location.pathname.replace(/\/+$/, '');
            $('.sidebar-menu a').removeClass('active');

            $('.sidebar-menu a').each(function () {
                const href = $(this).attr('href');
                if (!href || href === '#') return;

                try {
                    const linkUrl = new URL(href, window.location.origin);
                    const linkPath = linkUrl.pathname.replace(/\/+$/, '');

                    if (
                        currentPath === linkPath ||
                        (linkPath !== '' && linkPath !== '/' && currentPath.startsWith(linkPath))
                    ) {
                        $(this).addClass('active');
                    }
                } catch (e) {}
            });
        }

        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        function setSidebarTitles() {
            $('.sidebar-menu a').each(function () {
                const text = $(this).find('span').first().text().trim();
                if (text) {
                    $(this).attr('data-title', text);
                }
            });
        }

        setTimeout(function () {
            $loader.addClass('hidden');
        }, 300);

        initTheme();
        setSidebarTitles();
        syncLayout();
        highlightActiveMenuItem();

        $toggleBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            handleToggle();
        });

        $overlay.on('click', function () {
            closeMobileSidebar();
        });

        $(document).on('keydown', function (e) {
            if (e.ctrlKey && e.key.toLowerCase() === 'b' && !isMobileView()) {
                e.preventDefault();
                toggleDesktopSidebar();
            }

            if (e.key === 'Escape' && $sidebar.hasClass('active')) {
                closeMobileSidebar();
            }
        });

        $(document).on('click', '.sidebar-menu a', function () {
            if (isMobileView()) {
                closeMobileSidebar();
            }
        });

        let resizeTimer;
        $(window).on('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                syncLayout();
            }, 150);
        });

        window.app = {
            openMobileSidebar: function () {
                if (isMobileView()) openMobileSidebar();
            },
            closeMobileSidebar: function () {
                closeMobileSidebar();
            }
        };
    });
</script>

@stack('script')
</body>
</html>
