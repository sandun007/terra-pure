@php
    $wholesaler = auth('wholesaler')->user();
@endphp

<style>
    /* Modern Header Styles */
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
        top: 0;
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

    .header-left { flex: 0 0 auto; }
    .header-center { flex: 1; max-width: 600px; margin: 0 40px; }
    .header-right { flex: 0 0 auto; }

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

    .brand-logo img { height: 35px; width: auto; transition: all 0.3s ease; }
    .brand-logo:hover img { transform: scale(1.05); }

    .logo-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Search */
    .search-container { position: relative; width: 100%; }
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
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

    .search-input::placeholder { color: #94a3b8; font-weight: 400; }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 8px 30px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    /* Notification Button */
    .notification-btn {
        position: relative;
        width: 45px;
        height: 45px;
        border: none;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #64748b;
    }

    .notification-btn:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
    }

    .notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* User Dropdown */
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
        inset: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .user-btn:hover::before { opacity: 1; }
    .user-btn:hover { border-color: transparent; box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3); transform: translateY(-2px); }
    .user-btn:hover .user-name,
    .user-btn:hover .dropdown-icon { color: white; }

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

    .user-btn:hover .user-avatar { border-color: white; transform: scale(1.05); }

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
        white-space: nowrap;
    }

    .user-name small { font-size: 11px; font-weight: 500; color: #94a3b8; transition: all 0.3s ease; }
    .user-btn:hover .user-name small { color: rgba(255, 255, 255, 0.8); }

    .dropdown-icon { transition: all 0.3s ease; color: #64748b; position: relative; z-index: 1; }
    .user-btn[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); }

    /* Dropdown Menu */
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
        padding: 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .dropdown-header .profile-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .profile-details { flex: 1; color: white; }
    .profile-details h6 { margin: 0; font-size: 16px; font-weight: 700; color: white; }
    .profile-details small { font-size: 12px; color: rgba(255, 255, 255, 0.8); display: block; margin-top: 2px; }

    .business-name {
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

    .dropdown-divider { margin: 0; border-color: #f1f5f9 !important; }

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

    .dropdown-item svg { color: #94a3b8; transition: all 0.3s ease; }

    .dropdown-item:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%) !important;
        color: #667eea !important;
        padding-left: 30px !important;
    }

    .dropdown-item:hover svg { color: #667eea; transform: translateX(3px); }

    .logout-item:hover {
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.08) 0%, rgba(220, 38, 38, 0.08) 100%) !important;
        color: #ef4444 !important;
    }
    .logout-item:hover svg { color: #ef4444; }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        vertical-align: middle;
    }

    /* Mobile */
    @media (max-width: 991px) {
        .app-header { padding: 0 15px; height: 65px; }
        .header-center { display: none; }
        .brand-logo img { height: 28px; }
        .logo-badge { font-size: 10px; padding: 3px 8px; }
        .user-name { display: none; }
        .user-btn { padding: 4px; border-radius: 50%; }
        .user-avatar { width: 35px; height: 35px; }
    }
</style>

<header class="app-header">
    <!-- Left -->
    <div class="header-left">
        <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <a href="{{ route('wholesaler.dashboard') }}" class="brand-logo">
            <img src="{{ asset('asset/img/wordmark.png') }}" alt="DreamX Logo">
            <small class="logo-badge">Wholesale</small>
        </a>
    </div>

    <!-- Center -->
    <div class="header-center">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Search products, orders, customers..." aria-label="Search">
        </div>
    </div>

    <!-- Right -->
    <div class="header-right">
        <!-- Notifications -->
        <div class="dropdown">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge">3</span>
            </button>

            <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 320px;">
                <div class="p-3 border-bottom">
                    <strong>Notifications</strong>
                </div>
                <div class="p-3 text-muted">
                    No new notifications.
                </div>
            </div>
        </div>

        <!-- User -->
        <div class="dropdown">
            <button class="user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img
                    src="{{ $wholesaler?->img ? asset('storage/'.$wholesaler->img) : asset('asset/img/default-avatar.png') }}"
                    class="user-avatar"
                    alt="User Avatar"
                    onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'"
                >
                <span class="user-name">
                    {{ $wholesaler?->name ?? 'Guest' }}
                    <small>Wholesaler</small>
                </span>
                <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            <div class="dropdown-menu dropdown-menu-end">
                <div class="dropdown-header">
                    <img
                        src="{{ $wholesaler?->img ? asset('storage/'.$wholesaler->img) : asset('asset/img/default-avatar.png') }}"
                        alt="Avatar"
                        class="profile-img"
                        onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'"
                    >

                    <div class="profile-details">
                        <h6>{{ $wholesaler?->name ?? 'Guest' }}</h6>
                        <small><span class="status-dot"></span> Active Now</small>

                        @if($wholesaler?->business_name)
                            <div class="business-name">🏢 {{ $wholesaler->business_name }}</div>
                        @endif
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('wholesaler.profile') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    My Profile
                </a>

                <a class="dropdown-item" href="{{ route('wholesaler.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>

                <a class="dropdown-item" href="{{ route('wholesaler.orders.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Orders
                </a>

                <a class="dropdown-item" href="{{ route('wholesaler.products.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m7.5 4.27 9 5.15"></path>
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                        <path d="m3.3 7 8.7 5 8.7-5"></path>
                        <path d="M12 22V12"></path>
                    </svg>
                    Products
                </a>

                <a class="dropdown-item" href="{{ route('wholesaler.shop.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
                        <path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"></path>
                        <path d="M7 21h10"></path>
                        <path d="M12 3v18"></path>
                        <path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"></path>
                    </svg>
                    My Shop
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('wholesaler.logout') }}" method="POST" class="m-0">
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
    // Only UI extras (NO dropdown manual toggle - Bootstrap handles it)

    document.addEventListener('DOMContentLoaded', function () {
        // Header shadow on scroll
        const header = document.querySelector('.app-header');
        if (header) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 10) header.classList.add('scrolled');
                else header.classList.remove('scrolled');
            });
        }

        // Search focus animation (optional)
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('focus', () => {
                searchInput.parentElement.style.transform = 'scale(1.02)';
            });
            searchInput.addEventListener('blur', () => {
                searchInput.parentElement.style.transform = 'scale(1)';
            });
        }

        // Mark current dropdown item active
        const currentPath = window.location.pathname;
        document.querySelectorAll('.dropdown-item[href]').forEach((a) => {
            const href = a.getAttribute('href');
            if (!href) return;

            try {
                const url = new URL(href, window.location.origin);
                const path = url.pathname;

                if (path === currentPath || (path !== '/' && currentPath.startsWith(path))) {
                    a.style.background = 'linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%)';
                    a.style.color = '#667eea';
                    a.style.borderLeft = '3px solid #667eea';
                }
            } catch (e) {
                // ignore invalid href
            }
        });
    });
</script>
