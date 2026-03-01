<ul>
    <!-- MAIN DASHBOARD -->
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
            <!-- Wholesaler Products -->
            <li>
                <a href="{{ route('employee.wholesaler.products') }}"
                   class="{{ request()->routeIs('employee.wholesaler.products') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <path d="M12 11v6"></path>
                        <path d="M9 14h6"></path>
                    </svg>
                    <span>Wholesaler Products</span>
                </a>
            </li>

            <!-- Wholesaler Orders -->
            <li>
                <a href="{{ route('employee.wholesaler_orders.index') }}"
                   class="{{ request()->routeIs('employee.wholesaler_orders.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Wholesaler Orders</span>
                </a>
            </li>

            <!-- Today's Orders -->
            <li>
                <a href="{{ route('employee.wholesaler_orders.today') }}"
                   class="{{ request()->routeIs('employee.wholesaler_orders.today') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Today's Orders</span>
                </a>
            </li>

            <!-- Weekly Orders -->
            <li>
                <a href="{{ route('employee.wholesaler_orders.weekly') }}"
                   class="{{ request()->routeIs('employee.wholesaler_orders.weekly') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Weekly Orders</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- SELLER MANAGEMENT -->
    <li class="submenu-open">
        <h6 class="submenu-hdr">Seller Management</h6>
        <ul>
            <!-- All Seller Products -->
            <li>
{{--                <a href="{{ route('employee.seller.products.index') }}"--}}
{{--                   class="{{ request()->routeIs('employee.seller.products.*') ? 'active' : '' }}">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"--}}
{{--                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>--}}
{{--                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>--}}
{{--                        <line x1="12" y1="11" x2="12" y2="17"></line>--}}
{{--                        <line x1="9" y1="14" x2="15" y2="14"></line>--}}
{{--                    </svg>--}}
                    <span>Seller Products</span>
                    @php
                        $pendingCount = \App\Models\SellerProduct::whereNull('approved_by')
                            ->where('status', 'active')
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Pending Approvals -->
            <li>
{{--                <a href="{{ route('employee.seller.products.index', ['approval' => 'pending']) }}"--}}
{{--                   class="{{ request()->get('approval') == 'pending' ? 'active' : '' }}">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"--}}
{{--                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <circle cx="12" cy="12" r="10"></circle>--}}
{{--                        <polyline points="12 6 12 12 16 14"></polyline>--}}
{{--                    </svg>--}}
{{--                    <span>Pending Approval</span>--}}
{{--                    @if($pendingCount > 0)--}}
{{--                        <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>--}}
{{--                    @endif--}}
{{--                </a>--}}
            </li>

            <!-- Price Change Requests -->
{{--            <li>--}}
{{--                <a href="{{ route('employee.seller.products.price-history', ['status' => 'pending']) }}"--}}
{{--                   class="{{ request()->routeIs('employee.seller.products.price-history') && request('status') == 'pending' ? 'active' : '' }}">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"--}}
{{--                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>--}}
{{--                    </svg>--}}
{{--                    <span>Price Change Requests</span>--}}
{{--                    @php--}}
{{--                        $pricePendingCount = \App\Models\EmployeePriceChange::where('status', 'pending')--}}
{{--                            ->where('product_type', 'seller')--}}
{{--                            ->count();--}}
{{--                    @endphp--}}
{{--                    @if($pricePendingCount > 0)--}}
{{--                        <span class="badge bg-warning ms-auto">{{ $pricePendingCount }}</span>--}}
{{--                    @endif--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <!-- Price History -->--}}
{{--            <li>--}}
{{--                <a href="{{ route('employee.seller.products.price-history') }}"--}}
{{--                   class="{{ request()->routeIs('employee.seller.products.price-history') && !request('status') ? 'active' : '' }}">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"--}}
{{--                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                        <circle cx="12" cy="12" r="10"></circle>--}}
{{--                        <polyline points="12 6 12 12 16 14"></polyline>--}}
{{--                    </svg>--}}
{{--                    <span>Price History</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <!-- Seller Orders (existing) -->
            <li>
                <a href="{{ route('employee.delivery_calls.pending') }}"
                   class="{{ request()->routeIs('employee.delivery_calls.*') ? 'active' : '' }}">
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
