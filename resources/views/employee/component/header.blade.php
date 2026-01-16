<style>
    /* Modern Header Styles for Employee */
    .app-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        padding: 0 30px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        margin-top: -70px;
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .app-header.scrolled {
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
        height: 90px;
    }

    /* Header Sections */
    .header-left,
    .header-center,
    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .header-left {
        flex: 0 0 auto;
    }

    .header-center {
        flex: 1;
        max-width: 600px;
        margin: 0 40px;
    }

    .header-right {
        flex: 0 0 auto;
    }

    /* Brand Logo */
    .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 8px 12px;
        border-radius: 12px;
    }

    .brand-logo:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transform: translateY(-2px);
    }

    .brand-logo img {
        height: 35px;
        width: auto;
        transition: all 0.3s ease;
    }

    .brand-logo:hover img {
        transform: scale(1.05);
    }

    .logo-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    /* Search Container */
    .search-container {
        position: relative;
        width: 100%;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        height: 48px;
        padding: 0 20px 0 50px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        background: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .search-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .search-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 8px 30px rgba(16, 185, 129, 0.15);
        transform: translateY(-2px);
    }

    .search-input:focus + .search-icon {
        color: #10b981;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px 8px 8px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .user-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .user-btn:hover::before {
        opacity: 1;
    }

    .user-btn:hover {
        border-color: transparent;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        transform: translateY(-2px);
    }

    .user-btn:hover .user-name,
    .user-btn:hover .dropdown-icon {
        color: white;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .user-btn:hover .user-avatar {
        border-color: white;
        transform: scale(1.05);
    }

    .user-name {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.3;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .user-name small {
        font-size: 11px;
        font-weight: 500;
        color: #94a3b8;
        transition: all 0.3s ease;
    }

    .user-btn:hover .user-name small {
        color: rgba(255, 255, 255, 0.8);
    }

    .dropdown-icon {
        transition: all 0.3s ease;
        color: #64748b;
        position: relative;
        z-index: 1;
    }

    .user-btn[aria-expanded="true"] .dropdown-icon {
        transform: rotate(180deg);
    }

    /* Dropdown Menu */
    .dropdown-menu {
        margin-top: 12px !important;
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        padding: 0 !important;
        min-width: 280px;
        animation: dropdownSlide 0.3s ease;
        overflow: hidden;
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-header {
        padding: 25px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        gap: 15px;
        border-radius: 16px 16px 0 0;
    }

    .dropdown-header .profile-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
        color: rgba(255, 255, 255, 0.8);
        display: block;
        margin-top: 2px;
    }

    .employee-role {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .dropdown-divider {
        margin: 0;
        border-color: #f1f5f9 !important;
    }

    .dropdown-item {
        padding: 14px 25px !important;
        display: flex !important;
        align-items: center;
        gap: 12px;
        color: #475569 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        cursor: pointer;
        border: none !important;
        background: transparent !important;
        width: 100%;
        text-align: left;
    }

    .dropdown-item svg {
        color: #94a3b8;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%) !important;
        color: #10b981 !important;
        padding-left: 30px !important;
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

    /* Status Dot */
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

    /* Responsive Design */
    @media (max-width: 1024px) {
        .header-center {
            max-width: 400px;
            margin: 0 20px;
        }

        .app-header {
            padding: 0 20px;
        }
    }

    @media (max-width: 991px) {
        .app-header {
            padding: 0 15px;
            height: 65px;
        }

        .header-center {
            display: none;
        }

        .header-left {
            gap: 10px;
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
            width: 35px;
            height: 35px;
        }

        .mobile-menu-toggle {
            margin-right: 10px;
        }
    }

    @media (max-width: 480px) {
        .app-header {
            padding: 0 10px;
        }

        .header-right {
            gap: 10px;
        }
    }

    /* Smooth Transitions */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>

<header class="app-header">
    <!-- Left Section: Logo + Mobile Toggle -->
    <div class="header-left">
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

    <!-- Center Section: Search Bar -->
    <div class="header-center">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Search orders, products, wholesalers..." aria-label="Search">
        </div>
    </div>

    <!-- Right Section: User Menu -->
    <div class="header-right">
        <!-- User Dropdown -->
        <div class="user-dropdown">
            <button class="user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                    $employee = auth()->guard('employee')->user();
                    $avatarUrl = $employee && $employee->img ? asset('storage/' . $employee->img) : asset('asset/img/default-avatar.png');
                @endphp
                <img src="{{ $avatarUrl }}"
                     alt="User Avatar"
                     class="user-avatar"
                     onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                <span class="user-name">
                    {{ auth()->guard('employee')->user()->name ?? 'Employee' }}
                    <small>Staff</small>
                </span>
                <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <!-- Enhanced Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-end">
                <div class="dropdown-header">
                    <img src="{{ $avatarUrl }}"
                         alt="Avatar"
                         class="profile-img"
                         onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                    <div class="profile-details">
                        <h6>{{ auth()->guard('employee')->user()->name ?? 'Employee' }}</h6>
                        <small>
                            <span class="status-dot"></span>
                            Active Now
                        </small>
                        <div class="employee-role">
                            @if(auth()->guard('employee')->user()->role)
                                {{ ucfirst(auth()->guard('employee')->user()->role) }}
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

                <a class="dropdown-item" href="{{ route('employee.analytics') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 20V10"></path>
                        <path d="M12 20V4"></path>
                        <path d="M6 20v-6"></path>
                    </svg>
                    Analytics
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
                $(this).parent().css('transform', 'scale(1.02)');
            });

            searchInput.on('blur', function() {
                $(this).parent().css('transform', 'scale(1)');
            });
        }

        // Bootstrap dropdown functionality
        $('.user-btn').on('click', function(e) {
            e.stopPropagation();
            const dropdown = $(this).next('.dropdown-menu');
            const isExpanded = $(this).attr('aria-expanded') === 'true';

            // Close all other dropdowns
            $('.dropdown-menu').not(dropdown).removeClass('show');
            $('.user-btn').not(this).attr('aria-expanded', 'false');

            // Toggle current dropdown
            $(this).attr('aria-expanded', !isExpanded);
            dropdown.toggleClass('show', !isExpanded);
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.user-dropdown').length) {
                $('.dropdown-menu').removeClass('show');
                $('.user-btn').attr('aria-expanded', 'false');
            }
        });

        // Add active state to current page in dropdown
        const currentPath = window.location.pathname;
        $('.dropdown-item[href]').each(function() {
            const href = $(this).attr('href');
            if (href === currentPath || currentPath.startsWith(href) && href !== '/') {
                $(this).css({
                    'background': 'linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%)',
                    'color': '#10b981',
                    'border-left': '3px solid #10b981'
                });
            }
        });
    });
</script>
