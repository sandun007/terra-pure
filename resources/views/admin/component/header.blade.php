<div class="header">

    <!-- Logo -->
    <div class="header-left active">
        <a href="" class="logo logo-normal">
            <img src="{{asset('asset/img/theme/logo.webp')}}logo.webp" alt="">
        </a>
        <a href="" class="logo logo-white">
            <img src="{{asset('asset/img/theme/logo-white.webp')}}" alt="">
        </a>
        <a href="" class="logo-small">
            <img src="Dreams%20Pos%20Admin%20Template_files/logo-small.webp" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-chevrons-left feather-16">
                <polyline points="11 17 6 12 11 7"></polyline>
                <polyline points="18 17 13 12 18 7"></polyline>
            </svg>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
				<span class="bar-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- Search -->
        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#" class="dropdown">
                    <div class="searchinputs dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown"
                         data-bs-auto-close="false">
                        <input type="text" placeholder="Search">
                        <div class="search-addon">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                           stroke-linejoin="round" class="feather feather-x-circle feather-14"><circle
                                            cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line
                                            x1="9" y1="9" x2="15" y2="15"></line></svg></span>
                        </div>
                    </div>
                    <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">
                        <div class="search-info">
                            <h6><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                           stroke-linecap="round" stroke-linejoin="round"
                                           class="feather feather-search feather-16"><circle cx="11" cy="11"
                                                                                             r="8"></circle><line
                                            x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>Recent
                                Searches
                            </h6>
                            <ul class="search-tags">
                                <li><a href="javascript:void(0);">Products</a></li>
                                <li><a href="javascript:void(0);">Sales</a></li>
                                <li><a href="javascript:void(0);">Applications</a></li>
                            </ul>
                        </div>
                        <div class="search-info">
                            <h6><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                           stroke-linecap="round" stroke-linejoin="round"
                                           class="feather feather-help-circle feather-16"><circle cx="12" cy="12"
                                                                                                  r="10"></circle><path
                                            d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17"
                                                                                                  x2="12.01"
                                                                                                  y2="17"></line></svg></span>Help
                            </h6>
                            <p>How to Change Product Volume from 0 to 200 on Inventory management</p>
                            <p>Change Product Name</p>
                        </div>
                        <div class="search-info">
                            <h6><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                           stroke-linecap="round" stroke-linejoin="round"
                                           class="feather feather-user feather-16"><path
                                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12"
                                                                                                         cy="7"
                                                                                                         r="4"></circle></svg></span>Customers
                            </h6>
                            <ul class="customers">
                                <li>
                                    <a href="javascript:void(0);">Aron Varu<img
                                            src="Dreams%20Pos%20Admin%20Template_files/avator1.jpg" alt=""
                                            class="img-fluid"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Jonita<img
                                            src="Dreams%20Pos%20Admin%20Template_files/avatar-01.webp" alt=""
                                            class="img-fluid"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Aaron<img
                                            src="Dreams%20Pos%20Admin%20Template_files/avatar-10.webp" alt=""
                                            class="img-fluid"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <!-- /Search -->


        <!-- Select Store -->
        <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
						<span class="user-info">
							<span class="user-letter">
								<img src="Dreams%20Pos%20Admin%20Template_files/store-01.webp" alt="Store Logo"
                                     class="img-fluid">
							</span>
							<span class="user-detail">
								<span class="user-name">Select Store</span>
							</span>
						</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/store-01.webp" alt="Store Logo"
                         class="img-fluid"> Grocery Alpha
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/store-02.webp" alt="Store Logo"
                         class="img-fluid"> Grocery Apex
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/store-03.webp" alt="Store Logo"
                         class="img-fluid"> Grocery Bevy
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/store-04.webp" alt="Store Logo"
                         class="img-fluid"> Grocery Eden
                </a>
            </div>
        </li>
        <!-- /Select Store -->

        <!-- Flag -->
        <li class="nav-item dropdown has-arrow flag-nav nav-item-box">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                <img src="Dreams%20Pos%20Admin%20Template_files/us.webp" alt="Language" class="img-fluid">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript:void(0);" class="dropdown-item active">
                    <img src="Dreams%20Pos%20Admin%20Template_files/us.webp" alt="" height="16"> English
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/fr.webp" alt="" height="16"> French
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/es.webp" alt="" height="16"> Spanish
                </a>
                <a href="javascript:void(0);" class="dropdown-item">
                    <img src="Dreams%20Pos%20Admin%20Template_files/de.webp" alt="" height="16"> German
                </a>
            </div>
        </li>
        <!-- /Flag -->

        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-maximize">
                    <path
                        d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                </svg>
            </a>
        </li>
        <li class="nav-item nav-item-box">
            <a href="https://dreamspos.dreamstechnologies.com/html/template/email.html">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-mail">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <span class="badge rounded-pill">1</span>
            </a>
        </li>
        <!-- Notifications -->
        <li class="nav-item dropdown nav-item-box">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-bell">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="badge rounded-pill">2</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/activities.html">
                                <div class="media d-flex">
											<span class="avatar flex-shrink-0">
												<img alt="" src="Dreams%20Pos%20Admin%20Template_files/avatar-02.jpg">
											</span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> added
                                            new task <span class="noti-title">Patient appointment booking</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/activities.html">
                                <div class="media d-flex">
											<span class="avatar flex-shrink-0">
												<img alt="" src="Dreams%20Pos%20Admin%20Template_files/avatar-03.webp">
											</span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Tarah Shropshire</span>
                                            changed the task name <span class="noti-title">Appointment booking
													with payment gateway</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">6 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/activities.html">
                                <div class="media d-flex">
											<span class="avatar flex-shrink-0">
												<img alt="" src="Dreams%20Pos%20Admin%20Template_files/avatar-06.jpg">
											</span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Misty Tison</span>
                                            added <span class="noti-title">Domenic Houston</span> and <span
                                                class="noti-title">Claire Mapes</span> to project <span
                                                class="noti-title">Doctor available module</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">8 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/activities.html">
                                <div class="media d-flex">
											<span class="avatar flex-shrink-0">
												<img alt="" src="Dreams%20Pos%20Admin%20Template_files/avatar-17.webp">
											</span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Rolland Webber</span>
                                            completed task <span class="noti-title">Patient and Doctor video conferencing</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/activities.html">
                                <div class="media d-flex">
											<span class="avatar flex-shrink-0">
												<img alt="" src="Dreams%20Pos%20Admin%20Template_files/avatar-13.jpg">
											</span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span>
                                            added new task <span class="noti-title">Private chat module</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="">View all Notifications</a>
                </div>
            </div>
        </li>
        <!-- /Notifications -->

        <li class="nav-item nav-item-box">
            <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-settings">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
						<span class="user-info">
							<span class="user-letter">
								<img src="Dreams%20Pos%20Admin%20Template_files/avator1.jpg" alt="" class="img-fluid">
							</span>
							<span class="user-detail">
								<span class="user-name">John Smilga</span>
								<span class="user-role">Super Admin</span>
							</span>
						</span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
								<span class="user-img"><img src="Dreams%20Pos%20Admin%20Template_files/avator1.jpg"
                                                            alt="">
									<span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>John Smilga</h6>
                            <h5>Super Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item"
                       href="https://dreamspos.dreamstechnologies.com/html/template/profile.html">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-user me-2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        My Profile</a>
                    <a class="dropdown-item"
                       href="https://dreamspos.dreamstechnologies.com/html/template/general-settings.html">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-settings me-2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0"
                       href="https://dreamspos.dreamstechnologies.com/html/template/signin.html"><img
                            src="Dreams%20Pos%20Admin%20Template_files/log-out.svg" class="me-2"
                            alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
           aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="https://dreamspos.dreamstechnologies.com/html/template/profile.html">My
                Profile</a>
            <a class="dropdown-item"
               href="https://dreamspos.dreamstechnologies.com/html/template/general-settings.html">Settings</a>
            <a class="dropdown-item" href="https://dreamspos.dreamstechnologies.com/html/template/signin.html">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
