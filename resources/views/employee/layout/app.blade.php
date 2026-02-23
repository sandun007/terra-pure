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
    <title>@stack('title') - Employee Dashboard</title>
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

        /* Dark Theme Variables */
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

        .loader-text {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-align: center;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
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
            color: var(--primary);
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
            background: none;
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
            background: var(--sidebar-active);
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

        .menu-icon svg {
            width: 20px;
            height: 20px;
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

        /* Badge for notifications */
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

        /* Mobile Menu Toggle */
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

        /* Content Area */
        .content {
            flex: 1;
            padding: 25px;
            background: var(--light);
            min-height: calc(100vh - var(--header-height));
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

            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                border-radius: 10px;
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
        }

        /* Print Styles */
        @media print {
            .sidebar,
            .mobile-menu-toggle,
            .sidebar-overlay {
                display: none !important;
            }

            .page-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .content {
                padding: 0;
                background: white;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus styles for accessibility */
        *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        *:focus:not(:focus-visible) {
            outline: none;
        }

        /* Smooth transitions */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for entire page */
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

        /* Selection color */
        ::selection {
            background-color: var(--primary-light);
            color: var(--dark);
        }
    </style>

    @stack('css')
</head>
<body>
<div id="global-loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading Dashboard...</div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('employee.dashboard') }}" class="sidebar-logo" aria-label="Employee Dashboard">
                <img src="{{ asset('asset/img/dx.png') }}" alt="DreamX Logo">
                <span class="logo-text">Employee Panel</span>
            </a>
            <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar" title="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        <div class="sidebar-menu">
            @include('employee.component.sidebar')
        </div>
    </div>

    <!-- Page Wrapper -->
    <div class="page-wrapper" id="pageWrapper">
        <!-- Header Component -->
        @include('employee.component.header')

        <!-- Main Content -->
        <main class="content" id="mainContent">
            @yield('content')
        </main>

        <!-- Footer (Optional) -->
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
<script src="{{asset('asset/js/theme/jquery-3.7.1.min.js')}}"></script>
<!-- Bootstrap Core JS -->
<script src="{{asset('asset/js/theme/bootstrap.bundle.min.js')}}"></script>
<!-- Custom JS -->
<script src="{{asset('asset/js/theme/script.js')}}"></script>

<script>
    // Wait for DOM to be fully loaded
    $(document).ready(function () {
        initializeApp();
    });

    function initializeApp() {
        // Hide loader with delay
        setTimeout(function () {
            $('#global-loader').fadeOut(500);
        }, 300);

        // Initialize sidebar state
        let sidebarState = localStorage.getItem('sidebarState') || 'expanded';
        const isMobile = window.innerWidth <= 991;
        const sidebar = $('#sidebar');
        const pageWrapper = $('#pageWrapper');
        const sidebarOverlay = $('#sidebarOverlay');
        const mobileMenuToggle = $('#mobileMenuToggle');
        const sidebarToggle = $('#sidebarToggle');

        // Set initial state
        if (isMobile) {
            sidebar.removeClass('collapsed');
            pageWrapper.removeClass('collapsed');
            sidebar.addClass('mobile');
        } else {
            if (sidebarState === 'collapsed') {
                toggleSidebar(true);
            }
        }

        // Desktop sidebar toggle
        sidebarToggle.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!isMobile) {
                toggleSidebar();
            }
        });

        // Mobile menu toggle
        mobileMenuToggle.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (isMobile) {
                toggleMobileMenu();
            }
        });

        // Close sidebar when clicking overlay on mobile
        sidebarOverlay.on('click', function (e) {
            e.preventDefault();
            if (isMobile) {
                closeMobileMenu();
            }
        });

        // Close mobile menu when clicking a link
        if (isMobile) {
            $(document).on('click', '.sidebar-menu a', function (e) {
                e.stopPropagation();
                closeMobileMenu();
            });
        }

        // Handle window resize
        let resizeTimer;
        $(window).on('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResize, 250);
        });

        // Add tooltips to sidebar links
        $('.sidebar-menu a').each(function () {
            const text = $(this).find('span').text();
            $(this).attr('data-title', text);
        });

        // Initialize active menu highlighting
        highlightActiveMenuItem();

        // Update active menu on navigation
        $(document).on('click', '.sidebar-menu a', function (e) {
            if (!$(this).hasClass('logout-link')) {
                setTimeout(highlightActiveMenuItem, 100);
            }
        });

        // Prevent dropdown from closing when clicking inside
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });

        // Keyboard shortcuts
        $(document).on('keydown', handleKeyboardShortcuts);

        // Initialize theme
        initTheme();

        // Header scroll effect
        $(window).on('scroll', function () {
            const header = $('.app-header');
            if ($(this).scrollTop() > 50) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        });

        // Functions
        function toggleSidebar(silent = false) {
            sidebar.toggleClass('collapsed');
            pageWrapper.toggleClass('collapsed');

            const isCollapsed = sidebar.hasClass('collapsed');
            localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');

            if (!silent) {
                // Dispatch custom event
                const event = new CustomEvent('sidebarToggle', {
                    detail: {collapsed: isCollapsed}
                });
                window.dispatchEvent(event);
            }
        }

        function toggleMobileMenu() {
            sidebar.toggleClass('active');
            sidebarOverlay.toggleClass('active');

            // Prevent body scroll when menu is open
            if (sidebar.hasClass('active')) {
                $('body').addClass('menu-open');
                document.body.style.overflow = 'hidden';
            } else {
                $('body').removeClass('menu-open');
                document.body.style.overflow = '';
            }
        }

        function closeMobileMenu() {
            sidebar.removeClass('active');
            sidebarOverlay.removeClass('active');
            $('body').removeClass('menu-open');
            document.body.style.overflow = '';
        }

        function handleResize() {
            const newIsMobile = window.innerWidth <= 991;

            if (newIsMobile && !sidebar.hasClass('mobile')) {
                // Switch to mobile mode
                sidebar.removeClass('collapsed');
                pageWrapper.removeClass('collapsed');
                sidebar.addClass('mobile');
                sidebarOverlay.removeClass('active');
                $('body').removeClass('menu-open');
            } else if (!newIsMobile && sidebar.hasClass('mobile')) {
                // Switch to desktop mode
                sidebar.removeClass('mobile');
                sidebarOverlay.removeClass('active');
                sidebar.removeClass('active');
                $('body').removeClass('menu-open');
                const savedState = localStorage.getItem('sidebarState') || 'expanded';
                if (savedState === 'collapsed') {
                    toggleSidebar(true);
                }
            }
        }

        function highlightActiveMenuItem() {
            const currentPath = window.location.pathname;
            const currentUrl = window.location.href;

            $('.sidebar-menu a').removeClass('active');

            $('.sidebar-menu a').each(function () {
                const href = $(this).attr('href');
                if (href) {
                    // Exact match
                    if (currentPath === href) {
                        $(this).addClass('active');
                        return;
                    }

                    // Partial match for nested routes
                    if (href !== '/' && currentPath.startsWith(href)) {
                        $(this).addClass('active');
                    }

                    // Query parameter match
                    if (currentUrl.includes(href) && href.includes('?')) {
                        $(this).addClass('active');
                    }
                }
            });
        }

        function handleKeyboardShortcuts(e) {
            // Ctrl/Cmd + B to toggle sidebar (desktop only)
            if ((e.ctrlKey || e.metaKey) && e.key === 'b' && !isMobile) {
                e.preventDefault();
                toggleSidebar();
            }

            // Escape to close mobile menu
            if (e.key === 'Escape' && isMobile && sidebar.hasClass('active')) {
                e.preventDefault();
                closeMobileMenu();
            }

            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = $('.search-input');
                if (searchInput.length) {
                    searchInput.focus();
                }
            }
        }

        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            // Dispatch theme change event
            const event = new CustomEvent('themeChange', {
                detail: {theme: savedTheme}
            });
            window.dispatchEvent(event);
        }

        // Error handling
        window.addEventListener('error', function (e) {
            console.error('Application error:', e.error);
        });

        // Unhandled promise rejection
        window.addEventListener('unhandledrejection', function (e) {
            console.error('Unhandled promise rejection:', e.reason);
        });

        // Offline/Online detection
        window.addEventListener('online', function () {
            showNotification('You are back online', 'success');
        });

        window.addEventListener('offline', function () {
            showNotification('You are offline', 'warning');
        });
    }

    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Add styles if not already present
        if (!$('#notification-styles').length) {
            $('<style id="notification-styles">').text(`
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    padding: 15px 20px;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease;
                    border-left: 4px solid var(--primary);
                    max-width: 350px;
                }
                .notification-success {
                    border-left-color: var(--success);
                }
                .notification-warning {
                    border-left-color: var(--warning);
                }
                .notification-error {
                    border-left-color: var(--danger);
                }
                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    color: var(--dark);
                }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `).appendTo('head');
        }

        // Auto remove
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    // Create back to top button if not exists
    if (!$('.back-to-top').length) {
        $('body').append('<button class="back-to-top" aria-label="Back to top" title="Back to Top">↑</button>');

        $('.back-to-top').click(function () {
            $('html, body').animate({scrollTop: 0}, 300);
            return false;
        });

        // Style back to top button
        $('<style>').text(`
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
            }
            .back-to-top:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            }
        `).appendTo('head');
    }

    // Expose functions to window for debugging
    window.app = {
        showNotification: showNotification,
        toggleSidebar: function () {
            const sidebar = $('#sidebar');
            const pageWrapper = $('#pageWrapper');
            sidebar.toggleClass('collapsed');
            pageWrapper.toggleClass('collapsed');
        },
        highlightActiveMenuItem: function () {
            const currentPath = window.location.pathname;
            $('.sidebar-menu a').removeClass('active');
            $('.sidebar-menu a').each(function () {
                const href = $(this).attr('href');
                if (href && currentPath.startsWith(href)) {
                    $(this).addClass('active');
                }
            });
        }
    };
</script>

@stack('script')
</body>
</html>
