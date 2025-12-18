<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
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

    <!-- Animate CSS for smooth transitions -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 110px;
            --transition-speed: 0.3s;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-bg: #ffffff;
            --sidebar-text: #475569;
            --sidebar-active: #f1f5f9;
        }

        /* Global Loader */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .whirly-loader {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loader-text {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Main Wrapper */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 25px 20px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 90px;
            background: white;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-logo img {
            height: 35px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo img {
            transform: scale(0.9);
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
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
            color: #64748b;
            transition: all 0.3s ease;
            background: #f1f5f9;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: rotate(180deg);
        }

        .sidebar.collapsed .sidebar-toggle-btn {
            transform: rotate(180deg);
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
            scrollbar-color: #cbd5e1 transparent;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #cbd5e1;
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
            letter-spacing: 0.5px;
            color: #94a3b8;
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
            overflow: hidden;
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
        }

        .sidebar-menu li a:hover {
            background: var(--sidebar-active);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-menu li a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 0 2px 2px 0;
        }

        .sidebar-menu li a svg {
            min-width: 20px;
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

        /* Cart Badge */
        .cart-count-badge {
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 11px;
            font-weight: 600;
            line-height: 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .sidebar.collapsed .cart-count-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            margin: 0;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        /* Tooltips for collapsed sidebar */
        .sidebar.collapsed li a::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed li a:hover::after {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 15px);
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 0 0 60px rgba(0, 0, 0, 0.1);
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
        }

        @media (max-width: 767px) {
            :root {
                --header-height: 65px;
            }

            .sidebar {
                width: 100%;
                max-width: 280px;
            }

            .page-wrapper .content {
                padding: 15px;
            }

            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }
        }

        @media (max-width: 480px) {
            .page-wrapper .content {
                padding: 12px;
            }

            .sidebar-menu {
                padding: 15px 10px;
            }

            .sidebar-menu li a {
                padding: 10px 12px;
                font-size: 14px;
            }
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 25px;
            background: #f8fafc;
            min-height: calc(100vh - var(--header-height));
        }

        /* Smooth transitions */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>

    @stack('css')
</head>
<body>
<div id="global-loader">
    <div class="whirly-loader"></div>
    <div class="loader-text">Loading Dashboard...</div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('wholesaler.dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('asset/img/dx.png') }}" alt="Logo">
                <span class="logo-text">Wholesale Pro</span>
            </a>
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        <div class="sidebar-menu">
            @include('wholesaler.component.sidebar')
        </div>
    </div>

    <!-- Page Wrapper -->
    <div class="page-wrapper" id="pageWrapper">
        <!-- Header Component -->
        @include('wholesaler.component.header')

        <!-- Main Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('asset/js/theme/jquery-3.7.1.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap Core JS -->
<script src="{{asset('asset/js/theme/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<!-- Custom JS -->
<script src="{{asset('asset/js/theme/script.js')}}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        // Hide loader
        $('#global-loader').fadeOut(500);

        // Initialize sidebar state
        let sidebarState = localStorage.getItem('sidebarState') || 'expanded';
        const isMobile = window.innerWidth <= 991;

        // Set initial state
        if (isMobile) {
            $('#sidebar').removeClass('collapsed');
            $('#pageWrapper').removeClass('collapsed');
            $('#sidebar').addClass('mobile');
        } else {
            if (sidebarState === 'collapsed') {
                toggleSidebar(true);
            }
        }

        // Desktop sidebar toggle
        $('#sidebarToggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!isMobile) {
                toggleSidebar();
            }
        });

        // Mobile menu toggle
        $('#mobileMenuToggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (isMobile) {
                toggleMobileMenu();
            }
        });

        // Close sidebar when clicking overlay on mobile
        $('#sidebarOverlay').on('click', function(e) {
            e.preventDefault();
            if (isMobile) {
                closeMobileMenu();
            }
        });

        // Close mobile menu when clicking a link
        if (isMobile) {
            $(document).on('click', '.sidebar-menu a', function(e) {
                e.stopPropagation();
                closeMobileMenu();
            });
        }

        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (isMobile && $('#sidebar').hasClass('active')) {
                if (!$(e.target).closest('#sidebar').length &&
                    !$(e.target).closest('#mobileMenuToggle').length) {
                    closeMobileMenu();
                }
            }
        });

        // Handle window resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const newIsMobile = window.innerWidth <= 991;

                if (newIsMobile && !$('#sidebar').hasClass('mobile')) {
                    // Switch to mobile mode
                    $('#sidebar').removeClass('collapsed');
                    $('#pageWrapper').removeClass('collapsed');
                    $('#sidebar').addClass('mobile');
                    $('#sidebarOverlay').removeClass('active');
                    $('body').removeClass('menu-open');
                } else if (!newIsMobile && $('#sidebar').hasClass('mobile')) {
                    // Switch to desktop mode
                    $('#sidebar').removeClass('mobile');
                    $('#sidebarOverlay').removeClass('active');
                    $('#sidebar').removeClass('active');
                    $('body').removeClass('menu-open');
                    const savedState = localStorage.getItem('sidebarState') || 'expanded';
                    if (savedState === 'collapsed') {
                        toggleSidebar(true);
                    }
                }
            }, 250);
        });

        // Add tooltips to sidebar links
        $('.sidebar-menu a').each(function() {
            const text = $(this).find('span').text();
            $(this).attr('data-title', text);
        });

        // Update cart count


        // Initial cart count update
        updateCartCount();
        // Update cart count every 30 seconds
        setInterval(updateCartCount, 30000);

        // Functions
        function toggleSidebar(silent = false) {
            const sidebar = $('#sidebar');
            const pageWrapper = $('#pageWrapper');

            sidebar.toggleClass('collapsed');
            pageWrapper.toggleClass('collapsed');

            const isCollapsed = sidebar.hasClass('collapsed');
            localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');

            if (!silent) {
                // Add animation class for smooth transition
                sidebar.addClass('transitioning');
                setTimeout(() => {
                    sidebar.removeClass('transitioning');
                }, 300);
            }
        }

        function toggleMobileMenu() {
            const sidebar = $('#sidebar');
            const overlay = $('#sidebarOverlay');

            sidebar.toggleClass('active');
            overlay.toggleClass('active');

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
            $('#sidebar').removeClass('active');
            $('#sidebarOverlay').removeClass('active');
            $('body').removeClass('menu-open');
            document.body.style.overflow = '';
        }

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl + B to toggle sidebar (desktop only)
            if (e.ctrlKey && e.key === 'b' && !isMobile) {
                e.preventDefault();
                toggleSidebar();
            }

            // Escape to close mobile menu
            if (e.key === 'Escape' && isMobile && $('#sidebar').hasClass('active')) {
                e.preventDefault();
                closeMobileMenu();
            }
        });

        // Search functionality
        const searchInput = $('.search-input');
        if (searchInput.length) {
            let searchTimeout;

            searchInput.on('input', function(e) {
                clearTimeout(searchTimeout);
                const searchTerm = e.target.value.trim();

                if (searchTerm.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        performSearch(searchTerm);
                    }, 300);
                }
            });

            searchInput.on('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch($(this).val().trim());
                }
            });
        }

        function performSearch(term) {
            // Implement your search logic here
            console.log('Searching for:', term);

            // Example: Show loading state
            searchInput.addClass('searching');

            // Simulate API call
            setTimeout(() => {
                searchInput.removeClass('searching');
                // Handle search results
            }, 500);
        }

        // Add active class to current page in sidebar
        function highlightActiveMenuItem() {
            const currentPath = window.location.pathname;
            const currentUrl = window.location.href;

            $('.sidebar-menu a').removeClass('active');

            $('.sidebar-menu a').each(function() {
                const href = $(this).attr('href');
                if (href) {
                    // Exact match or starts with
                    if (currentPath === href ||
                        currentPath.startsWith(href) && href !== '/') {
                        $(this).addClass('active');
                    }
                    // Handle query parameters
                    else if (currentUrl.includes(href)) {
                        $(this).addClass('active');
                    }
                }
            });
        }

        // Initial highlight
        highlightActiveMenuItem();

        // Update on navigation
        $(document).on('click', '.sidebar-menu a', function(e) {
            if (!$(this).hasClass('logout-link')) {
                setTimeout(highlightActiveMenuItem, 100);
            }
        });

        // Handle dropdown clicks
        $(document).on('click', '.user-btn', function(e) {
            e.stopPropagation();
        });

        // Close dropdowns when clicking outside
        $(document).on('click', function() {
            $('.dropdown-menu').removeClass('show');
            $('.user-btn').attr('aria-expanded', 'false');
        });

        // Prevent dropdown from closing when clicking inside
        $(document).on('click', '.dropdown-menu', function(e) {
            e.stopPropagation();
        });
    });

    // Header scroll effect
    $(window).on('scroll', function() {
        const header = $('.app-header');
        if ($(this).scrollTop() > 50) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });
</script>

@stack('script')
</body>
</html>
