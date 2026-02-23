<style>
    /* Seller Sidebar – Light Color Theme */

    .seller-badge {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #7c2d12;
        font-weight: 600;
    }

    .sidebar-menu li a {
        color: #64748b;
        transition: all 0.25s ease;
    }

    .sidebar-menu li a:hover {
        background: rgba(249, 115, 22, 0.08);
        color: #ffffff;
    }

    .sidebar-menu li a.active {
        background: linear-gradient(
            135deg,
            rgba(249, 115, 22, 0.12),
            rgba(249, 115, 22, 0.06)
        );
        color: #ffffff;
        font-weight: 500;
    }

    .sidebar-menu li a.active::before {
        background: linear-gradient(135deg, #fdba74, #ffffff);
    }

    /* Menu badge – lighter red */
    .menu-badge {
        background: #fecaca;
        color: #991b1b;
        font-size: 11px;
        font-weight: 600;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        margin-left: auto;
        margin-right: 5px;
    }

    /* CSV upload badge – blue */
    .menu-badge-blue {
        background: #dbeafe;
        color: #1e40af;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 9px;
        margin-left: auto;
        margin-right: 5px;
        letter-spacing: 0.3px;
    }

    /* Product Management Section */
    .product-management-section {
        border-left: 3px solid #ffffff;
        background: linear-gradient(
            90deg,
            rgb(255, 255, 255) 0%,
            transparent 100%
        );
        margin: 10px 0;
        border-radius: 0 8px 8px 0;
    }

    /* Submenu */
    .submenu {
        margin-left: 25px;
        padding: 6px 0;
        border-left: 1px solid #ffffff;
    }

    .submenu li a {
        padding: 8px 15px !important;
        font-size: 13px !important;
        color: #94a3b8 !important;
        border-radius: 6px;
        margin-bottom: 4px;
        transition: all 0.2s ease;
    }

    .submenu li a:hover {
        background: rgba(249, 115, 22, 0.08) !important;
        color: #fb923c !important;
    }

    .submenu li a.active {
        background: rgba(249, 115, 22, 0.12) !important;
        color: #fb923c !important;
        font-weight: 500;
    }

    /* Parent item arrow */
    .has-submenu > a::after {
        content: '›';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%) rotate(90deg);
        font-size: 16px;
        color: #cbd5f5;
        transition: all 0.3s ease;
    }

    .has-submenu > a.active::after {
        color: #fb923c;
    }
</style>


<ul>
    <!-- MAIN SECTION -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Main</h6>
        <ul>
            <li>
                <a href="{{ route('seller.dashboard') }}"
                   class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Seller Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('seller.shop.seller.index') }}"
                   class="{{ request()->routeIs('seller.shop') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    <span>Shop</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- PRODUCT MANAGEMENT -->
    <li class="submenu-open product-management-section">
        <h6 class="submenu-hdr">Product Management</h6>
        <ul>
            <!-- PRODUCTS -->
            <li class="has-submenu">
                <a href="{{ route('seller.product.index') }}"
                   class="{{ request()->routeIs('seller.product.index') || request()->routeIs('seller.product.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>My Products</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('seller.product.create') }}"
                           class="{{ request()->routeIs('seller.product.create') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Add New Product</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- BRANDS -->
            <li class="has-submenu">
                <a href="{{ route('seller.brands.index') }}"
                   class="{{ request()->routeIs('seller.brand.index') || request()->routeIs('seller.brand.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                    <span>My Brand</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('seller.brands.create') }}"
                           class="{{ request()->routeIs('seller.brand.create') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Add New Brand</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- CATEGORIES -->
            <li class="has-submenu">
                <a href="{{ route('seller.category.index') }}"
                   class="{{ request()->routeIs('seller.category.index') || request()->routeIs('seller.category.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span>My Category</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('seller.category.create') }}"
                           class="{{ request()->routeIs('seller.category.create') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Add New Category</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>


    <!-- ORDER MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Order Management</h6>
        <ul>
            <!-- New Order -->
            <li>
                <a href="{{ route('seller.new-order') }}"
                   class="{{ request()->routeIs('seller.new-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>New Orders</span>
                    @php $newOrdersCount = 0; @endphp
                    @if($newOrdersCount > 0)
                        <span class="menu-badge">{{ $newOrdersCount }}</span>
                    @endif
                </a>
            </li>

            <!-- ★ CSV / Leads Upload ★ -->
            <li>
                <a href="{{ route('seller.order.csv-upload') }}"
                   class="{{ request()->routeIs('seller.order.csv-upload') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <span>Upload CSV / Leads</span>
                    <span class="menu-badge-blue">NEW</span>
                </a>
            </li>

            <!-- Pending Orders -->
            <li>
                <a href="{{ route('seller.pending-order') }}"
                   class="{{ request()->routeIs('seller.pending-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Pending Orders</span>
                </a>
            </li>

            <!-- Completed Orders -->
            <li>
                <a href="{{ route('seller.complete-order') }}"
                   class="{{ request()->routeIs('seller.complete-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Completed Orders</span>
                </a>
            </li>

            <!-- Rescheduled Orders -->
            <li>
                <a href="{{ route('seller.rescheduling-order') }}"
                   class="{{ request()->routeIs('seller.rescheduling-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Rescheduled Orders</span>
                </a>
            </li>

            <!-- Return Orders -->
            <li>
                <a href="{{ route('seller.return-order') }}"
                   class="{{ request()->routeIs('seller.return-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7v6a9 9 0 0 0 9 9 9 9 0 0 0 9-9V7"></path>
                        <polyline points="9 10 12 13 15 10"></polyline>
                        <line x1="12" y1="13" x2="12" y2="3"></line>
                    </svg>
                    <span>Return Orders</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- PAYMENT MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Payment Management</h6>
        <ul>
            <li>
                <a href="{{ route('seller.my_payment') }}"
                   class="{{ request()->routeIs('seller.my_payment') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span>My Payment</span>
                </a>
            </li>

            <li>
                <a href="{{ route('seller.withdraw') }}"
                   class="{{ request()->routeIs('seller.withdraw') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                    <span>Withdraw</span>
                </a>
            </li>

            <li>
                <a href="{{ route('seller.payment_history') }}"
                   class="{{ request()->routeIs('seller.payment_history') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Payment History</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- CALL MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Call Management</h6>
        <ul>
            <li>
                <a href="{{ route('call.index') }}"
                   class="{{ request()->routeIs('call.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <span>Pending Call List</span>
                </a>
            </li>

            <li>
                <a href="{{ route('call.conform') }}"
                   class="{{ request()->routeIs('call.conform') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Conform Call List</span>
                </a>
            </li>

            <li>
                <a href="{{ route('call.reject') }}"
                   class="{{ request()->routeIs('call.reject') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <span>Reject Call List</span>
                </a>
            </li>

            <li>
                <a href="{{ route('call.not_answer_call_list') }}"
                   class="{{ request()->routeIs('call.not_answer_call_list') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        <line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                    <span>Not Answer List</span>
                </a>
            </li>

            <li>
                <a href="{{ route('call.other_call_list') }}"
                   class="{{ request()->routeIs('call.other_call_list') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Other Call List</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- SALES MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Sales</h6>
        <ul>
            <li>
                <a href="#" class="{{ request()->is('seller/sales') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Sales</span>
                </a>
            </li>

            <li>
                <a href="#" class="{{ request()->is('seller/invoices') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Invoices</span>
                </a>
            </li>

            <li>
                <a href="#" class="{{ request()->is('seller/sales-return') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span>Sales Return</span>
                </a>
            </li>

            <li>
                <a href="#" class="{{ request()->is('seller/quotation') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Quotation</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- REPORTS -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Reports</h6>
        <ul>
            <li>
                <a href="#" class="{{ request()->is('seller/reports/sales') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Sales Report</span>
                </a>
            </li>

            <li>
                <a href="#" class="{{ request()->is('seller/reports/inventory') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                        <path
                            d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                    </svg>
                    <span>Inventory Report</span>
                </a>
            </li>

            <li>
                <a href="#" class="{{ request()->is('seller/reports/customers') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Customer Report</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- ACCOUNT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Account</h6>
        <ul>
            <li>
                <a href="{{ route('profile.index') }}"
                   class="{{ request()->routeIs('seller.profile.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>My Profile</span>
                </a>
            </li>

            <li>
                <form action="{{ route('seller.logout') }}" method="POST" id="seller-logout-form">
                    @csrf
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('seller-logout-form').submit();"
                       class="text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </li>
</ul>
