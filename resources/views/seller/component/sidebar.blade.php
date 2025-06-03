<style>
    /* Outer wrapper */
    .sidebar {
        height: 100vh; /* Full viewport height */
        overflow: hidden; /* Prevent double scrollbars */
        /*position: fixed;*/
        top: 20;
        left: 0;
        width: 250px; /* Adjust as needed */
        /*background: #fff;*/
        /*z-index: 1000;*/
    }

    /* Inner scrollable container */
    .sidebar-inner {
        height: 100%;
        overflow-y: auto;
        padding-bottom: 50px; /* Optional: ensures space for last item */
    }

    /* Optional: make sure ul stretches properly */
    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
</style>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="submenu">
                            <a href="{{route('seller.dashboard')}}" class="subdrop active">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-grid">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                <span>Seller Dashboard</span></a>
                        </li>
                        <li>
                            <a href="{{route('seller.shop')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-layers">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                                <span>Shop</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="#" id="orderDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-box mr-1">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span>Order</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="orderDropdown">
                                <a class="dropdown-item" href="{{route('seller.new-order')}}">New Order</a>
                                <a class="dropdown-item" href="{{route('seller.complete-order')}}">Complete Order</a>
                                <a class="dropdown-item" href="{{route('seller.rescheduling-order')}}">Reschedule
                                    Order</a>
                                <a class="dropdown-item" href="{{route('seller.return-order')}}">Return Order</a>
                            </div>
                        </li>

                        <li>
                            <a href="{{route('seller.product.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-plus-square">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                <span>My Product</span></a></li>

                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="#" id="orderDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-box mr-1">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span>Payment</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="orderDropdown">
                                <a class="dropdown-item" href="{{route('seller.my_payment')}}">My Payment</a>
                                <a class="dropdown-item" href="{{route('seller.withdraw')}}">Withdraw</a>
                                <a class="dropdown-item" href="{{route('seller.payment_history')}}">Payment History</a>
{{--                                <a class="dropdown-item" href="">Return Order</a>--}}
                            </div>
                        </li>
                        <li>
                            <a href="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-codepen">
                                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                                    <line x1="12" y1="22" x2="12" y2="15.5"></line>
                                    <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                                    <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="8.5"></line>
                                </svg>
                                <span>Call</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li><a href="https://dreamspos.dreamstechnologies.com/html/template/sales-list.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-shopping-cart">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path
                                        d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>Sales</span></a></li>
                        <li>
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/invoice-report.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <span>Invoices</span></a></li>
                        <li><a href="https://dreamspos.dreamstechnologies.com/html/template/sales-returns.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-copy">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span>Sales Return</span></a></li>
                        <li>
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/quotation-list.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-save">
                                    <path
                                        d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span>Quotation</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li><a href="https://dreamspos.dreamstechnologies.com/html/template/sales-report.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-bar-chart-2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                                <span>Sales Report</span></a></li>
                        <li>
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/inventory-report.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path
                                        d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                </svg>
                                <span>Inventory Report</span></a></li>
                        <li>

                            <a href="https://dreamspos.dreamstechnologies.com/html/template/customer-report.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Customer Report</span></a></li>
                        <li>

                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                <span>General Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/general-settings.html">Profile</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/security-settings.html">Security</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/notification.html">Notifications</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">

                            <ul>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/system-settings.html">System
                                        Settings</a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/company-settings.html">Company
                                        Settings </a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/localization-settings.html">Localization</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/prefixes.html">Prefixes</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/preference.html">Preference</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/appearance.html">Appearance</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/social-authentication.html">Social
                                        Authentication</a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/language-settings.html">Language</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">

                            <ul>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/invoice-settings.html">Invoice</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/printer-settings.html">Printer</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/pos-settings.html">POS</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/custom-fields.html">Custom
                                        Fields</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-monitor">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                <span>System Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/email-settings.html">Email</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/sms-gateway.html">SMS
                                        Gateways</a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/otp-settings.html">OTP</a>
                                </li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/gdpr-settings.html">GDPR
                                        Cookies</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                <span>Payment Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/bank-settings-grid.html">Bank
                                        Accounts</a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/tax-rates.html">Tax
                                        Rates</a></li>
                                <li>
                                    <a href="https://dreamspos.dreamstechnologies.com/html/template/currency-settings.html">Currencies</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="https://dreamspos.dreamstechnologies.com/html/template/signin.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Logout</span> </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
    <div class="slimScrollBar"
         style="background: rgb(204, 204, 204); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 174.757px;"></div>
    <div class="slimScrollRail"
         style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
</div>
</div>

<script>
    document.getElementById('toggle-sidebar').addEventListener('click', function () {
        const sidebar = document.getElementById('application-sidebar');
        const content = document.getElementById('main-content');

        sidebar.classList.toggle('sidebar-collapsed');
        content.classList.toggle('content-expanded');
    });


</script>
