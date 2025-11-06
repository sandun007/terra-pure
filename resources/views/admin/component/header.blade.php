<div class="header d-flex align-items-center justify-content-between flex-wrap px-3" style="padding: 10px;">
    <style>
        @media (max-width: 988px) {
            #toggle_btn,
            #search-box,
            #maxi {
                display: none !important;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .user-menu {
                margin-left: 0;
                width: 100%;
                justify-content: flex-end;
            }
        }

        .nav.user-menu {
            margin-left: auto;
        }

        .dropdown.mobile-user-menu .dropdown-menu {
            right: 0;
            left: auto;
        }
    </style>

    <!-- Logo and Toggle -->
    <div class="d-flex align-items-center" style="width: 200px; margin-left: 50px;">
        <a href="{{route('admin.dashboard')}}" class="logo logo-normal">
            <img src="{{ asset('asset/img/wordmark.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);" class="ms-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" class="feather feather-chevrons-left feather-16">
                <polyline points="11 17 6 12 11 7"></polyline>
                <polyline points="18 17 13 12 18 7"></polyline>
            </svg>
        </a>
    </div>

    <!-- Mobile Menu Icon -->
    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu d-flex align-items-center">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info d-flex align-items-center">
                    <span class="user-letter">
                        <img src="{{ asset('storage/' . auth()->user()->img) }}" alt="" class="img-fluid rounded-circle" width="40" height="40">
                    </span>
                    <span class="user-detail ms-2 d-none d-md-block">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">Admin</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user dropdown-menu-end" style="border-radius: 10px; border: 1px solid black;">
                <div class="profilename p-3" >
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-3">
                            <img src="{{ asset('storage/' . auth()->user()->img) }}" alt="" class="rounded-circle" width="50">
                            <span class="status online"></span>
                        </span>
                        <div class="profilesets">
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">Admin</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <a class="dropdown-item" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-user me-2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        My Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-settings me-2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65..."/>
                        </svg>
                        Settings
                    </a>
                    <hr class="my-2">
                    <a class="dropdown-item logout pb-0" href="#">
                        <img src="#" class="me-2" alt="img" width="16"> Logout
                    </a>
                </div>
            </div>
        </li>
    </ul>

    <!-- Mobile Dropdown -->
    <div class="dropdown mobile-user-menu ">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
           aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right" style="background-color: #979797">
            <a class="dropdown-item text-white" href="#">My Profile</a>
            <a class="dropdown-item text-white" href="#">Settings</a>
            <form action="{{ route('seller.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>
            </form>
        </div>
    </div>
</div>
