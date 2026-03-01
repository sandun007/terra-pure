{{-- resources/views/employee/layouts/app.blade.php --}}
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
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
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

        /* Global Loader */
        #global-loader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
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
            overflow: visible !important; /* IMPORTANT: prevent clipping on mobile */
        }

        /* Sidebar */
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
            color: var(--primary);
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0; width: 0; margin: 0; overflow: hidden;
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

        .sidebar.collapsed .sidebar-toggle-btn:hover { transform: rotate(0deg); }

        /* Sidebar Menu */
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 20px 15px;
            scrollbar-width: thin;
            scrollbar-color: var(--gray-light) transparent;
        }

        .sidebar-menu ul { list-style: none; padding: 0; margin: 0; }

        .submenu-hdr {
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

        .sidebar.collapsed .submenu-hdr {
            opacity: 0; height: 0; margin: 0; padding: 0; overflow: hidden;
        }

        .sidebar-menu li { position: relative; }

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
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-menu li a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 0 2px 2px 0;
        }

        .sidebar.collapsed li a span { opacity: 0; width: 0; overflow: hidden; }
        .sidebar.collapsed li a { justify-content: center; padding: 12px; }

        /* Tooltips when collapsed */
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

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(5px);
            z-index: 1040;
            opacity: 0;
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar-overlay.active { display: block; opacity: 1; }

        /* Mobile Menu Toggle Button (in header) */
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
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            flex-shrink: 0;
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        /* Content */
        .content {
            flex: 1;
            padding: 25px;
            background: var(--light);
            min-height: calc(100vh - var(--header-height));
        }

        /* ==========================
           MOBILE FIX (IMPORTANT)
           ========================== */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex; }

            /* Sidebar becomes OFFCANVAS */
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 280px !important;
                height: 100vh !important;
                transform: translateX(-110%) !important;
                transition: transform 0.3s ease !important;
                z-index: 1050 !important;
                box-shadow: 8px 0 40px rgba(0,0,0,0.15);
            }

            .sidebar.active {
                transform: translateX(0) !important;
            }

            .page-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .sidebar-toggle-btn { display: none; }

            .content { padding: 18px; }
        }

        @media (max-width: 767px) {
            .content { padding: 14px; }
        }

        /* Print */
        @media print {
            .sidebar, .mobile-menu-toggle, .sidebar-overlay { display: none !important; }
            .page-wrapper { margin-left: 0 !important; width: 100% !important; }
            .content { padding: 0; background: white; }
        }

        /* Focus */
        *:focus { outline: 2px solid var(--primary); outline-offset: 2px; }
        *:focus:not(:focus-visible) { outline: none; }

        /* Scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--light); }
        ::-webkit-scrollbar-thumb { background: var(--gray-light); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray); }

        ::selection { background-color: var(--primary-light); color: var(--dark); }
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

            <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar" title="Toggle Sidebar" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        <nav class="sidebar-menu">
            {{-- Your sidebar menu --}}
            @include('employee.component.sidebar')
        </nav>
    </aside>

    <!-- Page Wrapper -->
    <div class="page-wrapper" id="pageWrapper">
        {{-- Header (contains the #mobileMenuToggle button) --}}
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
        // Loader
        setTimeout(function () {
            $('#global-loader').fadeOut(500);
        }, 250);

        const $sidebar = $('#sidebar');
        const $pageWrapper = $('#pageWrapper');
        const $overlay = $('#sidebarOverlay');
        const $sidebarToggle = $('#sidebarToggle');
        const $mobileMenuToggle = $('#mobileMenuToggle'); // MUST exist in header include

        // Desktop: restore collapsed state
        const savedSidebarState = localStorage.getItem('sidebarState') || 'expanded';
        if (window.innerWidth > 991 && savedSidebarState === 'collapsed') {
            $sidebar.addClass('collapsed');
            $pageWrapper.addClass('collapsed');
        }

        // Desktop toggle button
        $sidebarToggle.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (window.innerWidth <= 991) return;

            $sidebar.toggleClass('collapsed');
            $pageWrapper.toggleClass('collapsed');

            const isCollapsed = $sidebar.hasClass('collapsed');
            localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
        });

        // Mobile toggle button (HAMBURGER)
        $(document).on('click', '#mobileMenuToggle', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (window.innerWidth > 991) return;

            $sidebar.toggleClass('active');
            $overlay.toggleClass('active');

            if ($sidebar.hasClass('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Close when overlay click
        $overlay.on('click', function () {
            closeMobile();
        });

        // Close on ESC
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $sidebar.hasClass('active')) {
                closeMobile();
            }
        });

        // Close after click menu link (mobile)
        $(document).on('click', '.sidebar-menu a', function () {
            if (window.innerWidth <= 991) closeMobile();
        });

        // Tooltips for collapsed sidebar (desktop)
        $('.sidebar-menu a').each(function () {
            const text = $(this).find('span').first().text().trim();
            if (text) $(this).attr('data-title', text);
        });

        // Active menu highlight (simple)
        highlightActiveMenuItem();
        $(document).on('click', '.sidebar-menu a', function () {
            setTimeout(highlightActiveMenuItem, 100);
        });

        // Theme init
        initTheme();

        // Resize handling
        let resizeTimer;
        $(window).on('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                if (window.innerWidth > 991) {
                    // Ensure mobile state cleared
                    $sidebar.removeClass('active');
                    $overlay.removeClass('active');
                    document.body.style.overflow = '';
                }
            }, 150);
        });

        function closeMobile() {
            $sidebar.removeClass('active');
            $overlay.removeClass('active');
            document.body.style.overflow = '';
        }

        function highlightActiveMenuItem() {
            const currentPath = window.location.pathname;
            $('.sidebar-menu a').removeClass('active');

            $('.sidebar-menu a').each(function () {
                const href = $(this).attr('href');
                if (!href) return;

                // Works for /route and nested /route/...
                if (href !== '/' && currentPath.startsWith(href)) {
                    $(this).addClass('active');
                } else if (currentPath === href) {
                    $(this).addClass('active');
                }
            });
        }

        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        // Debug (optional)
        window.app = {
            openMobileSidebar: function () {
                if (window.innerWidth <= 991) {
                    $sidebar.addClass('active');
                    $overlay.addClass('active');
                    document.body.style.overflow = 'hidden';
                }
            },
            closeMobileSidebar: function () {
                $sidebar.removeClass('active');
                $overlay.removeClass('active');
                document.body.style.overflow = '';
            }
        };
    });
</script>

@stack('script')
</body>
</html>
