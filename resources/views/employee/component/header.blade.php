@php
    $employee = auth()->guard('employee')->user();
    $avatarUrl = $employee && $employee->img ? asset('storage/' . $employee->img) : asset('asset/img/default-avatar.png');
@endphp

<style>
    .app-header {
        position: fixed;
        top: 0;
        right: 0;
        left: var(--sidebar-width);
        height: var(--header-height);
        z-index: 1080;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 0 24px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .page-wrapper.collapsed .app-header {
        left: var(--sidebar-collapsed-width);
    }

    .app-header.scrolled {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.98);
    }

    .header-left,
    .header-center,
    .header-right {
        display: flex;
        align-items: center;
    }

    .header-left {
        gap: 14px;
        flex: 0 0 auto;
        min-width: 0;
    }

    .header-center {
        flex: 1 1 auto;
        max-width: 600px;
        margin: 0 20px;
    }

    .header-right {
        flex: 0 0 auto;
        gap: 12px;
    }

    /* Toggle Button in Header */
    .header-toggle-btn {
        border: none;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
        transition: all 0.25s ease;
    }

    .header-toggle-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(16, 185, 129, 0.32);
    }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 6px 0;
    }

    .brand-logo img {
        height: 34px;
        width: auto;
        transition: all 0.3s ease;
    }

    .brand-logo:hover img {
        transform: scale(1.04);
    }

    .logo-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .search-container {
        position: relative;
        width: 100%;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        height: 46px;
        padding: 0 16px 0 46px;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        background: white;
        transition: all 0.25s ease;
        outline: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .search-input::placeholder {
        color: #94a3b8;
    }

    .search-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.10), 0 8px 24px rgba(16, 185, 129, 0.12);
    }

    .user-dropdown {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px 6px 6px;
        border: 1px solid #e2e8f0;
        background: white;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .user-btn:hover {
        box-shadow: 0 12px 26px rgba(16, 185, 129, 0.14);
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }

    .user-name {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        font-weight: 700;
        color: #1e293b;
        font-size: 13px;
        line-height: 1.2;
        position: relative;
        z-index: 1;
        white-space: nowrap;
    }

    .user-name small {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
    }

    .dropdown-icon {
        color: #64748b;
        transition: transform 0.25s ease;
        position: relative;
        z-index: 1;
    }

    .user-btn[aria-expanded="true"] .dropdown-icon {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        margin-top: 12px !important;
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        padding: 0 !important;
        min-width: 280px;
        overflow: hidden;
    }

    .dropdown-header {
        padding: 22px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dropdown-header .profile-img {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
    }

    .profile-details {
        flex: 1;
        color: white;
    }

    .profile-details h6 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: white;
    }

    .profile-details small {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.84);
        display: block;
        margin-top: 2px;
    }

    .employee-role {
        display: inline-block;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
        border: 1px solid rgba(255, 255, 255, 0.28);
    }

    .dropdown-divider {
        margin: 0;
        border-color: #f1f5f9 !important;
    }

    .dropdown-item {
        padding: 14px 22px !important;
        display: flex !important;
        align-items: center;
        gap: 12px;
        color: #475569 !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.25s ease !important;
        cursor: pointer;
        border: none !important;
        background: transparent !important;
        width: 100%;
        text-align: left;
    }

    .dropdown-item svg {
        color: #94a3b8;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .dropdown-item:hover {
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%) !important;
        color: #10b981 !important;
        padding-left: 26px !important;
    }

    .dropdown-item:hover svg {
        color: #10b981;
        transform: translateX(3px);
    }

    .logout-item:hover {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.08) 0%, rgba(220, 38, 38, 0.08) 100%) !important;
        color: #ef4444 !important;
    }

    .logout-item:hover svg {
        color: #ef4444;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @media (max-width: 1024px) {
        .header-center {
            max-width: 420px;
            margin: 0 16px;
        }

        .app-header {
            padding: 0 18px;
        }
    }

    @media (max-width: 991.98px) {
        .app-header {
            left: 0 !important;
            height: var(--mobile-header-height);
            padding: 0 16px;
        }

        .header-center {
            display: none;
        }

        .brand-logo img {
            height: 28px;
        }

        .logo-badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .user-name {
            display: none;
        }

        .user-btn {
            padding: 4px;
            border-radius: 50%;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
        }

        .dropdown-icon {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .app-header {
            padding: 0 10px;
        }

        .header-right {
            gap: 10px;
        }

        .header-toggle-btn {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }
    }
</style>

<header class="app-header" id="appHeader">
    <!-- Left Section -->
    <div class="header-left">
        <button class="header-toggle-btn" id="headerSidebarToggle" type="button" aria-label="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <a href="{{ route('employee.dashboard') }}" class="brand-logo">
            <img src="{{ asset('asset/img/wordmark.png') }}" alt="DreamX Logo">
            <small class="logo-badge">Employee</small>
        </a>
    </div>

    <!-- Center Section -->
    <div class="header-center">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Search orders, products, wholesalers..." aria-label="Search">
        </div>
    </div>

    <!-- Right Section -->
    <div class="header-right">
        <div class="user-dropdown">
            <button class="user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ $avatarUrl }}"
                     alt="User Avatar"
                     class="user-avatar"
                     onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                <span class="user-name">
                    {{ $employee->name ?? 'Employee' }}
                    <small>Staff</small>
                </span>
                <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <div class="dropdown-menu dropdown-menu-end">
                <div class="dropdown-header">
                    <img src="{{ $avatarUrl }}"
                         alt="Avatar"
                         class="profile-img"
                         onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                    <div class="profile-details">
                        <h6>{{ $employee->name ?? 'Employee' }}</h6>
                        <small>
                            <span class="status-dot"></span>
                            Active Now
                        </small>
                        <div class="employee-role">
                            @if($employee && $employee->role)
                                {{ ucfirst($employee->role) }}
                            @else
                                Employee
                            @endif
                        </div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('employee.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>

                <a class="dropdown-item" href="{{ route('employee.profile') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    My Profile
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('employee.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    $(document).ready(function() {
        const searchInput = $('.search-input');

        if (searchInput.length) {
            searchInput.on('focus', function() {
                $(this).parent().css('transform', 'scale(1.01)');
            });

            searchInput.on('blur', function() {
                $(this).parent().css('transform', 'scale(1)');
            });
        }

        const currentPath = window.location.pathname.replace(/\/+$/, '');
        $('.dropdown-item[href]').each(function() {
            const href = $(this).attr('href');
            if (!href || href === '#') return;

            try {
                const url = new URL(href, window.location.origin);
                const path = url.pathname.replace(/\/+$/, '');

                if (
                    path === currentPath ||
                    (path !== '' && path !== '/' && currentPath.startsWith(path))
                ) {
                    $(this).css({
                        'background': 'linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%)',
                        'color': '#10b981',
                        'border-left': '3px solid #10b981'
                    });
                }
            } catch (e) {}
        });

        $(window).on('scroll', function () {
            $('.app-header').toggleClass('scrolled', $(this).scrollTop() > 10);
        });
    });
</script>
