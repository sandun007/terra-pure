@php
    $wholesaler = auth('wholesaler')->user();
@endphp

<style>
    .app-header{
        position:fixed;
        top:0;
        right:0;
        left:var(--sidebar-width);
        height:var(--header-height);
        z-index:1100;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
        padding:0 24px;
        background:rgba(255,255,255,.94);
        backdrop-filter:blur(16px);
        border-bottom:1px solid rgba(226,232,240,.9);
        box-shadow:0 6px 24px rgba(15,23,42,.04);
        transition:all .3s ease;
    }

    .page-wrapper.collapsed .app-header{
        left:var(--sidebar-collapsed-width);
    }

    .app-header.scrolled{
        box-shadow:0 10px 30px rgba(15,23,42,.08);
        background:rgba(255,255,255,.98);
    }

    .header-left,
    .header-center,
    .header-right{
        display:flex;
        align-items:center;
    }

    .header-left{
        gap:14px;
        flex:0 0 auto;
        min-width:0;
    }

    .header-center{
        flex:1 1 auto;
        max-width:620px;
        margin:0 12px;
    }

    .header-right{
        flex:0 0 auto;
        gap:12px;
    }

    .sidebar-toggle-btn{
        border:none;
        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
        color:white;
        width:44px;
        height:44px;
        min-width:44px;
        border-radius:14px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        box-shadow:0 10px 24px rgba(102,126,234,.25);
        transition:all .25s ease;
    }

    .sidebar-toggle-btn:hover{
        transform:translateY(-2px);
        box-shadow:0 14px 30px rgba(102,126,234,.32);
    }

    .brand-logo{
        display:flex;
        align-items:center;
        gap:10px;
        text-decoration:none;
        min-width:0;
    }

    .brand-logo img{
        height:34px;
        width:auto;
        object-fit:contain;
        flex-shrink:0;
    }

    .logo-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:5px 10px;
        border-radius:999px;
        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
        color:#fff;
        font-size:11px;
        font-weight:700;
        letter-spacing:.05em;
        text-transform:uppercase;
        white-space:nowrap;
    }

    .search-container{
        position:relative;
        width:100%;
    }

    .search-icon{
        position:absolute;
        left:16px;
        top:50%;
        transform:translateY(-50%);
        color:#94a3b8;
        z-index:1;
        pointer-events:none;
    }

    .search-input{
        width:100%;
        height:46px;
        border:1.5px solid #e2e8f0;
        border-radius:14px;
        background:#fff;
        padding:0 16px 0 46px;
        font-size:14px;
        font-weight:500;
        color:#334155;
        outline:none;
        transition:all .25s ease;
        box-shadow:0 2px 10px rgba(15,23,42,.03);
    }

    .search-input:focus{
        border-color:#667eea;
        box-shadow:0 0 0 4px rgba(102,126,234,.10);
    }

    .notification-btn{
        position:relative;
        width:44px;
        height:44px;
        border:none;
        background:#fff;
        border-radius:14px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        color:#64748b;
        cursor:pointer;
        border:1px solid #e2e8f0;
        transition:all .25s ease;
    }

    .notification-btn:hover{
        color:#fff;
        border-color:transparent;
        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
        transform:translateY(-2px);
        box-shadow:0 10px 24px rgba(102,126,234,.24);
    }

    .notification-badge{
        position:absolute;
        top:-5px;
        right:-5px;
        width:20px;
        height:20px;
        border-radius:50%;
        background:linear-gradient(135deg,#f59e0b 0%,#ef4444 100%);
        color:#fff;
        border:2px solid #fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:10px;
        font-weight:700;
    }

    .user-btn{
        display:flex;
        align-items:center;
        gap:10px;
        min-height:46px;
        padding:6px 14px 6px 6px;
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:999px;
        cursor:pointer;
        transition:all .25s ease;
    }

    .user-btn:hover{
        transform:translateY(-2px);
        box-shadow:0 12px 26px rgba(15,23,42,.08);
        border-color:#cbd5e1;
    }

    .user-avatar{
        width:34px;
        height:34px;
        border-radius:50%;
        object-fit:cover;
        flex-shrink:0;
        border:2px solid #e2e8f0;
    }

    .user-name{
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        line-height:1.2;
        color:#0f172a;
        font-size:13px;
        font-weight:700;
        white-space:nowrap;
    }

    .user-name small{
        font-size:11px;
        color:#94a3b8;
        font-weight:600;
    }

    .dropdown-icon{
        color:#64748b;
        transition:transform .25s ease;
    }

    .user-btn[aria-expanded="true"] .dropdown-icon{
        transform:rotate(180deg);
    }

    .dropdown-menu{
        margin-top:12px !important;
        border:none !important;
        border-radius:18px !important;
        overflow:hidden;
        box-shadow:0 18px 50px rgba(15,23,42,.16) !important;
        padding:0 !important;
    }

    .dropdown-header{
        padding:22px;
        display:flex;
        align-items:center;
        gap:14px;
        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    }

    .dropdown-header .profile-img{
        width:56px;
        height:56px;
        border-radius:50%;
        object-fit:cover;
        border:3px solid rgba(255,255,255,.28);
    }

    .profile-details{
        flex:1;
        color:white;
        min-width:0;
    }

    .profile-details h6{
        margin:0;
        color:#fff;
        font-size:15px;
        font-weight:700;
    }

    .profile-details small{
        display:block;
        margin-top:3px;
        color:rgba(255,255,255,.86);
        font-size:12px;
    }

    .business-name{
        display:inline-flex;
        align-items:center;
        margin-top:8px;
        padding:5px 10px;
        border-radius:999px;
        font-size:11px;
        font-weight:700;
        color:#fff;
        background:rgba(255,255,255,.18);
        border:1px solid rgba(255,255,255,.28);
    }

    .dropdown-item{
        display:flex !important;
        align-items:center;
        gap:12px;
        padding:14px 20px !important;
        font-size:14px !important;
        font-weight:600 !important;
        color:#475569 !important;
        transition:all .2s ease !important;
        border:none !important;
        background:transparent !important;
    }

    .dropdown-item:hover{
        background:rgba(102,126,234,.08) !important;
        color:#667eea !important;
        padding-left:24px !important;
    }

    .dropdown-item svg{
        color:#94a3b8;
        flex-shrink:0;
    }

    .dropdown-item:hover svg{
        color:#667eea;
    }

    .logout-item:hover{
        background:rgba(239,68,68,.08) !important;
        color:#ef4444 !important;
    }

    .logout-item:hover svg{
        color:#ef4444;
    }

    .status-dot{
        width:8px;
        height:8px;
        border-radius:50%;
        background:#10b981;
        display:inline-block;
        margin-right:6px;
    }

    @media (max-width: 1199.98px){
        .app-header{
            padding:0 18px;
        }

        .header-center{
            max-width:430px;
        }

        .logo-badge{
            display:none;
        }
    }

    @media (max-width: 991.98px){
        .app-header{
            left:0 !important;
            height:var(--mobile-header-height);
            padding:0 16px;
        }

        .header-center{
            display:none;
        }

        .brand-logo img{
            height:28px;
        }

        .user-btn{
            padding-right:8px;
        }

        .user-name,
        .dropdown-icon{
            display:none;
        }
    }

    @media (max-width: 767.98px){
        .app-header{
            padding:0 12px;
        }

        .header-left{
            gap:10px;
        }

        .brand-logo img{
            height:24px;
        }

        .notification-btn,
        .sidebar-toggle-btn,
        .user-btn{
            min-width:40px;
            width:40px;
            height:40px;
        }

        .user-btn{
            padding:4px;
            justify-content:center;
            border-radius:50%;
        }

        .user-avatar{
            width:30px;
            height:30px;
        }

        .dropdown-menu{
            min-width:250px !important;
        }
    }

    @media (max-width: 480px){
        .app-header{
            height:58px;
            padding:0 10px;
        }

        .notification-btn,
        .sidebar-toggle-btn{
            width:38px;
            height:38px;
            min-width:38px;
            border-radius:12px;
        }

        .user-btn{
            width:38px;
            height:38px;
            min-width:38px;
        }

        .dropdown-menu{
            min-width:230px !important;
        }
    }
</style>

<header class="app-header">
    <div class="header-left">
        <button class="sidebar-toggle-btn" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

    <div class="header-center">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Search products, orders, customers..." aria-label="Search">
        </div>
    </div>

    <div class="header-right">
        <div class="dropdown">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge" id="notificationCount">3</span>
            </button>

            <div class="dropdown-menu dropdown-menu-end" style="min-width:320px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <strong class="fs-6">Notifications</strong>
                    <span class="badge bg-primary rounded-pill">3 new</span>
                </div>
                <div class="p-3 text-muted text-center">
                    <p class="mb-0">No new notifications.</p>
                </div>
            </div>
        </div>

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
                <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                <a class="dropdown-item" href="{{ route('wholesaler.profile') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('wholesaler.dashboard') }}">Dashboard</a>
                <a class="dropdown-item" href="{{ route('wholesaler.products.index') }}">Products</a>
                <a class="dropdown-item" href="{{ route('wholesaler.shop.index') }}">My Shop</a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('wholesaler.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
