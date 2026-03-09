<ul>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Main</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.dashboard') }}"
                   class="{{ request()->routeIs('wholesaler.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">My Shop</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.shop.index') }}"
                   class="{{ request()->routeIs('wholesaler.shop.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>Shop Home</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Product Management</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.products.index') }}"
                   class="{{ request()->routeIs('wholesaler.products.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>All Products</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.products.create') }}"
                   class="{{ request()->routeIs('wholesaler.products.create') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Add Product</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Brand Management</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.brands.index') }}"
                   class="{{ request()->routeIs('wholesaler.brands.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                    <span>Brands</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.brands.create') }}"
                   class="{{ request()->routeIs('wholesaler.brands.create') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Add Brand</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Category Management</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.categories.index') }}"
                   class="{{ request()->routeIs('wholesaler.categories.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.categories.create') }}"
                   class="{{ request()->routeIs('wholesaler.categories.create') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    <span>Add Category</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Order Management</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.order.pending-order') }}"
                   class="{{ request()->routeIs('wholesaler.order.pending-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Pending Orders</span>
                    @if(($pendingOrdersCount ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.order.complete-order') }}"
                   class="{{ request()->routeIs('wholesaler.order.complete-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Completed Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.order.return-order') }}"
                   class="{{ request()->routeIs('wholesaler.order.return-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12h10"></path>
                        <path d="M9 9l3-3-3-3"></path>
                        <path d="M12 21H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h10"></path>
                    </svg>
                    <span>Return Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wholesaler.order.rescheduling-order') }}"
                   class="{{ request()->routeIs('wholesaler.order.rescheduling-order') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Rescheduling Orders</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Payment</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.payments.index') }}"
                   class="{{ request()->routeIs('wholesaler.payments.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <span>Deposit History</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="submenu-open">
        <h6 class="submenu-hdr">Account</h6>
        <ul>
            <li>
                <a href="{{ route('wholesaler.profile') }}"
                   class="{{ request()->routeIs('wholesaler.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>My Profile</span>
                </a>
            </li>
            <li>
                <form action="{{ route('wholesaler.logout') }}" method="POST" id="logout-form-sidebar">
                    @csrf
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                       class="text-danger logout-link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
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
