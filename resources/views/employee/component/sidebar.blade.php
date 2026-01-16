<ul>
    <li class="submenu-open">
        <h6 class="submenu-hdr">Main</h6>
        <ul>
            <li>
                <a href="{{ route('employee.dashboard') }}"
                   class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
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

    <!-- WHOLESALER MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Wholesaler Management</h6>
        <ul>
            <li>
                <a href="{{ route('employee.wholesaler.products') }}"
                   class="{{ request()->routeIs('employee.wholesaler.products') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i>
                    <span>Wholesaler Products</span>
                </a>
            </li>

            <li>
                <a href="{{ route('employee.wholesaler_orders.index') }}"
                   class="{{ request()->routeIs('employee.wholesaler_orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <span>Wholesaler Orders</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- SELLER MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Seller Management</h6>
        <ul>
            <li>
                <a href="{{ route('seller.employee.seller.orders') }}"
                   class="{{ request()->routeIs('seller.employee.seller.orders') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Seller Orders</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- REPORTS -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Reports</h6>
        <ul>
            <li>
                <a href="{{ route('employee.analytics') }}"
                   class="{{ request()->routeIs('employee.analytics') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Analytics</span>
                </a>
            </li>
            <li>
                <a href="{{ route('price-changes.history') }}"
                   class="{{ request()->routeIs('price-changes.history') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    <span>Price Changes History</span>
                </a>
            </li>
            <li>
                <a href="{{ route('employee.export.report') }}?type=wholesaler_orders"
                   class="{{ request()->routeIs('employee.export.report') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Export Reports</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- PRINT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Print</h6>
        <ul>
            <li>
                <a href="#" id="printLabelsBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Print Labels</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- ACCOUNT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Account</h6>
        <ul>
            <li>
                <a href="{{ route('employee.profile') }}"
                   class="{{ request()->routeIs('employee.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>My Profile</span>
                </a>
            </li>
            <li>
                <form action="{{ route('employee.logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
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

<script>
    document.getElementById('printLabelsBtn').addEventListener('click', function(e) {
        e.preventDefault();

        // Show SweetAlert for order selection
        Swal.fire({
            title: 'Print Labels',
            html: `
            <div class="text-start">
                <p class="mb-3">Enter Order ID to print:</p>
                <input type="number" id="orderIdInput" class="form-control" placeholder="Enter Order ID" min="1">
                <div class="mt-3">
                    <p class="text-muted small">Or go to orders page to select multiple orders.</p>
                </div>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Print',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const orderId = document.getElementById('orderIdInput').value;
                if (!orderId) {
                    Swal.showValidationMessage('Please enter an Order ID');
                    return false;
                }
                return orderId;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const orderId = result.value;
                // Open print page in new tab
                window.open(`/employee/print/${orderId}`, '_blank');
            }
        });
    });
</script>
