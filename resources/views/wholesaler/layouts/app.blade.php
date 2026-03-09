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

    <link rel="stylesheet" href="{{ asset('asset/css/theme/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/theme/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/theme/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/theme/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/wholesaler.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --sidebar-width:270px;
            --sidebar-collapsed-width:84px;
            --header-height:76px;
            --mobile-header-height:60px;
            --primary:#667eea;
            --secondary:#764ba2;
            --body-bg:#f8fafc;
            --border:#e2e8f0;
            --sidebar-text:#475569;
            --sidebar-bg:#ffffff;
        }

        *{
            box-sizing:border-box;
        }

        html,body{
            margin:0;
            padding:0;
            min-height:100%;
            overflow-x:hidden;
            background:var(--body-bg);
        }

        body.sidebar-open{
            overflow:hidden;
        }

        #global-loader{
            position:fixed;
            inset:0;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            z-index:99999;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            color:#fff;
            transition:opacity .35s ease, visibility .35s ease;
        }

        #global-loader.hidden{
            opacity:0;
            visibility:hidden;
            pointer-events:none;
        }

        .whirly-loader{
            width:50px;
            height:50px;
            border:4px solid rgba(255,255,255,.25);
            border-top-color:#fff;
            border-radius:50%;
            animation:spin 1s linear infinite;
            margin-bottom:16px;
        }

        .loader-text{
            font-size:14px;
            font-weight:600;
            letter-spacing:.4px;
        }

        @keyframes spin{
            to{ transform:rotate(360deg); }
        }

        .main-wrapper{
            min-height:100vh;
            display:flex;
        }

        .sidebar{
            position:fixed;
            top:0;
            left:0;
            width:var(--sidebar-width);
            height:100vh;
            background:var(--sidebar-bg);
            border-right:1px solid var(--border);
            box-shadow:0 4px 20px rgba(15,23,42,.06);
            z-index:1200;
            display:flex;
            flex-direction:column;
            transition:width .3s ease, transform .3s ease;
            overflow:hidden;
        }

        .sidebar.collapsed{
            width:var(--sidebar-collapsed-width);
        }

        .sidebar-header{
            min-height:var(--header-height);
            padding:18px;
            border-bottom:1px solid var(--border);
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fff;
        }

        .sidebar-logo{
            display:flex;
            align-items:center;
            gap:12px;
            text-decoration:none;
            min-width:0;
        }

        .sidebar-logo img{
            width:40px;
            height:40px;
            object-fit:contain;
            flex-shrink:0;
        }

        .logo-text{
            font-size:18px;
            font-weight:800;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            transition:opacity .25s ease, width .25s ease;
        }

        .sidebar.collapsed .logo-text{
            opacity:0;
            width:0;
        }

        .sidebar-menu{
            flex:1;
            overflow-y:auto;
            overflow-x:hidden;
            padding:16px 12px 20px;
        }

        .sidebar-menu ul{
            list-style:none;
            margin:0;
            padding:0;
        }

        .submenu-hdr{
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:.08em;
            color:#94a3b8;
            font-weight:700;
            margin:18px 10px 10px;
            white-space:nowrap;
            transition:all .25s ease;
        }

        .sidebar.collapsed .submenu-hdr{
            opacity:0;
            height:0;
            margin:0;
            overflow:hidden;
        }

        .sidebar-menu li a{
            display:flex;
            align-items:center;
            gap:12px;
            min-height:48px;
            padding:12px 14px;
            margin-bottom:6px;
            border-radius:14px;
            text-decoration:none;
            color:var(--sidebar-text);
            font-size:14px;
            font-weight:600;
            white-space:nowrap;
            position:relative;
            transition:all .25s ease;
        }

        .sidebar-menu li a:hover{
            background:#f8fafc;
            color:var(--primary);
            transform:translateX(4px);
        }

        .sidebar-menu li a.active{
            color:var(--primary);
            background:linear-gradient(135deg, rgba(102,126,234,.12), rgba(118,75,162,.10));
        }

        .sidebar-menu li a.active::before{
            content:"";
            position:absolute;
            left:0;
            top:8px;
            bottom:8px;
            width:4px;
            border-radius:0 10px 10px 0;
            background:linear-gradient(180deg,var(--primary),var(--secondary));
        }

        .sidebar-menu li a svg{
            width:20px;
            height:20px;
            flex-shrink:0;
        }

        .sidebar.collapsed .sidebar-menu li a{
            justify-content:center;
            padding:12px;
        }

        .sidebar.collapsed .sidebar-menu li a span{
            opacity:0;
            width:0;
            overflow:hidden;
        }

        .sidebar.collapsed .sidebar-menu li a::after{
            content:attr(data-title);
            position:absolute;
            left:calc(100% + 14px);
            top:50%;
            transform:translateY(-50%);
            background:#0f172a;
            color:#fff;
            padding:8px 10px;
            border-radius:10px;
            font-size:12px;
            font-weight:600;
            white-space:nowrap;
            opacity:0;
            visibility:hidden;
            transition:all .2s ease;
            pointer-events:none;
            z-index:1300;
        }

        .sidebar.collapsed .sidebar-menu li a:hover::after{
            opacity:1;
            visibility:visible;
        }

        .sidebar-menu .badge{
            margin-left:auto;
            font-size:11px !important;
            font-weight:700;
            border-radius:999px;
            min-width:22px;
            padding:6px 8px;
        }

        .sidebar.collapsed .sidebar-menu .badge{
            position:absolute;
            top:4px;
            right:4px;
            margin-left:0;
        }

        .page-wrapper{
            width:calc(100% - var(--sidebar-width));
            margin-left:var(--sidebar-width);
            min-height:100vh;
            transition:margin-left .3s ease, width .3s ease;
        }

        .page-wrapper.collapsed{
            width:calc(100% - var(--sidebar-collapsed-width));
            margin-left:var(--sidebar-collapsed-width);
        }

        .content{
            min-height:100vh;
            padding:calc(var(--header-height) + 24px) 24px 24px;
            background:var(--body-bg);
        }

        .sidebar-overlay{
            position:fixed;
            inset:0;
            background:rgba(15,23,42,.45);
            backdrop-filter:blur(4px);
            z-index:1190;
            opacity:0;
            visibility:hidden;
            transition:all .25s ease;
        }

        .sidebar-overlay.active{
            opacity:1;
            visibility:visible;
        }

        @media (max-width: 991.98px){
            .sidebar{
                transform:translateX(-100%);
                width:min(86vw,300px);
                box-shadow:0 10px 30px rgba(15,23,42,.14);
            }

            .sidebar.active{
                transform:translateX(0) !important;
            }

            .sidebar.collapsed{
                width:min(86vw,300px);
            }

            .sidebar.collapsed .logo-text,
            .sidebar.collapsed .submenu-hdr,
            .sidebar.collapsed .sidebar-menu li a span{
                opacity:1;
                width:auto;
                visibility:visible;
            }

            .sidebar.collapsed .sidebar-menu li a{
                justify-content:flex-start;
                padding:12px 14px;
            }

            .sidebar.collapsed .sidebar-menu li a::after{
                display:none;
            }

            .page-wrapper,
            .page-wrapper.collapsed{
                width:100%;
                margin-left:0;
            }

            .content{
                padding:calc(var(--mobile-header-height) + 16px) 16px 16px;
            }

            .sidebar-header{
                min-height:var(--mobile-header-height);
                justify-content:flex-start;
            }
        }

        @media (max-width: 767.98px){
            .content{
                padding:calc(var(--mobile-header-height) + 14px) 14px 14px;
            }

            .sidebar-menu{
                padding:14px 10px 16px;
            }

            .sidebar-menu li a{
                min-height:44px;
                padding:10px 12px;
                border-radius:12px;
                font-size:13px;
            }

            .sidebar-logo img{
                width:34px;
                height:34px;
            }

            .logo-text{
                font-size:16px;
            }
        }

        @media (max-width: 480px){
            .content{
                padding:70px 12px 12px;
            }

            .sidebar{
                width:100%;
                max-width:300px;
            }
        }

        @media print{
            .sidebar,
            .sidebar-overlay,
            .app-header{
                display:none !important;
            }

            .page-wrapper,
            .page-wrapper.collapsed{
                width:100% !important;
                margin-left:0 !important;
            }

            .content{
                padding:0 !important;
            }
        }
    </style>

    @stack('css')
</head>
<body>
<div id="global-loader">
    <div class="whirly-loader"></div>
    <div class="loader-text">Loading Dashboard...</div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('wholesaler.dashboard') }}" class="sidebar-logo">
                <img src="{{ asset('asset/img/dx.png') }}" alt="Logo">
                <span class="logo-text">Wholesale Pro</span>
            </a>
        </div>

        <div class="sidebar-menu">
            @include('wholesaler.component.sidebar')
        </div>
    </aside>

    <div class="page-wrapper" id="pageWrapper">
        @include('wholesaler.component.header')

        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('asset/js/theme/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('asset/js/theme/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="{{ asset('asset/js/theme/script.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const sidebar = document.getElementById('sidebar');
        const pageWrapper = document.getElementById('pageWrapper');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const loader = document.getElementById('global-loader');

        if (typeof feather !== 'undefined' && feather && typeof feather.replace === 'function') {
            feather.replace();
        }

        function isMobile() {
            return window.innerWidth <= 991.98;
        }

        function setSidebarTitles() {
            document.querySelectorAll('.sidebar-menu a').forEach(function (link) {
                const span = link.querySelector('span');
                if (span) {
                    link.setAttribute('data-title', span.textContent.trim());
                }
            });
        }

        function openMobileSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            body.classList.add('sidebar-open');
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            body.classList.remove('sidebar-open');
        }

        function toggleDesktopSidebar() {
            sidebar.classList.toggle('collapsed');
            pageWrapper.classList.toggle('collapsed');

            localStorage.setItem(
                'sidebarState',
                sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded'
            );
        }

        function applyDesktopState() {
            closeMobileSidebar();

            const savedState = localStorage.getItem('sidebarState') || 'expanded';

            sidebar.classList.remove('active');
            overlay.classList.remove('active');

            if (savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
                pageWrapper.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                pageWrapper.classList.remove('collapsed');
            }
        }

        function applyMobileState() {
            sidebar.classList.remove('collapsed');
            pageWrapper.classList.remove('collapsed');
            closeMobileSidebar();
        }

        function syncLayout() {
            if (isMobile()) {
                applyMobileState();
            } else {
                applyDesktopState();
            }
        }

        function handleToggleClick(e) {
            e.preventDefault();
            e.stopPropagation();

            if (isMobile()) {
                if (sidebar.classList.contains('active')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                toggleDesktopSidebar();
            }
        }

        setSidebarTitles();
        syncLayout();

        if (loader) {
            setTimeout(function () {
                loader.classList.add('hidden');
            }, 300);
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', handleToggleClick);
            toggleBtn.addEventListener('touchstart', handleToggleClick, { passive: false });
        }

        if (overlay) {
            overlay.addEventListener('click', function () {
                closeMobileSidebar();
            });
        }

        document.querySelectorAll('.sidebar-menu a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile()) {
                    closeMobileSidebar();
                }
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.ctrlKey && e.key.toLowerCase() === 'b' && !isMobile()) {
                e.preventDefault();
                toggleDesktopSidebar();
            }

            if (e.key === 'Escape' && isMobile() && sidebar.classList.contains('active')) {
                closeMobileSidebar();
            }
        });

        document.addEventListener('click', function (e) {
            if (
                isMobile() &&
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target)
            ) {
                closeMobileSidebar();
            }
        });

        let resizeTimer = null;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                syncLayout();
            }, 120);
        });

        window.addEventListener('scroll', function () {
            const header = document.querySelector('.app-header');
            if (!header) return;
            header.classList.toggle('scrolled', window.scrollY > 10);
        });
    });
</script>

@stack('script')
</body>
</html>
