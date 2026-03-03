@php
    $seller = auth('seller')->user();
    $type = $seller?->type;

    $isSelfSeller   = ($type === 'self_seller');
    $isSystemSeller = ($type === 'system_seller');
@endphp

<style>
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
        color: #f97316;
    }

    .sidebar-menu li a.active {
        background: linear-gradient(135deg,rgba(249,115,22,0.12),rgba(249,115,22,0.06));
        color: #f97316;
        font-weight: 500;
    }

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

    .menu-badge-emerald {
        background: #d1fae5;
        color: #065f46;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 9px;
        margin-left: auto;
        margin-right: 5px;
        letter-spacing: 0.3px;
    }

    .product-management-section {
        border-left: 3px solid #f97316;
        background: linear-gradient(90deg,rgba(249,115,22,0.05) 0%,transparent 100%);
        margin: 10px 0;
        border-radius: 0 8px 8px 0;
    }

    .delivery-section {
        border-left: 3px solid #34d399;
        background: linear-gradient(90deg,rgba(52,211,153,0.08) 0%,transparent 100%);
        margin: 10px 0;
        border-radius: 0 8px 8px 0;
    }

    .call-management-section {
        border-left: 3px solid #8b5cf6;
        background: linear-gradient(90deg,rgba(139,92,246,0.05) 0%,transparent 100%);
        margin: 10px 0;
        border-radius: 0 8px 8px 0;
    }

    .order-management-section {
        border-left: 3px solid #3b82f6;
        background: linear-gradient(90deg,rgba(59,130,246,0.05) 0%,transparent 100%);
        margin: 10px 0;
        border-radius: 0 8px 8px 0;
    }

    .submenu {
        margin-left: 25px;
        padding: 6px 0;
        border-left: 1px solid #e2e8f0;
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
        background: rgba(249,115,22,0.08) !important;
        color: #fb923c !important;
    }

    .submenu li a.active {
        background: rgba(249,115,22,0.12) !important;
        color: #fb923c !important;
        font-weight: 500;
    }

    .has-submenu > a::after {
        content: '›';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%) rotate(90deg);
        font-size: 16px;
        color: #cbd5e1;
        transition: all 0.3s ease;
    }

    .has-submenu > a.active::after {
        color: #fb923c;
    }
</style>

<ul>
    {{-- ═══ MAIN ═══ --}}
    <li class="menu-section">Main</li>
    <ul>
        <li>
            <a href="{{ route('seller.dashboard') }}"
               class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
            </a>
        </li>

        @if(!$isSelfSeller)
            <li>
                <a href="{{ route('seller.shop.index') }}"
                   class="{{ request()->routeIs('seller.shop.*') ? 'active' : '' }}">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                    <span>Shop</span>
                </a>
            </li>
        @endif
    </ul>

    {{-- ═══ ORDER MANAGEMENT ═══ --}}
    <li class="menu-section order-management-section" style="color:#3b82f6">Order Management</li>
    <ul>
        <li class="has-submenu">
            <a href="{{ route('seller.order.pending') }}"
               class="{{ request()->routeIs('seller.order.*') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <span>Orders</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('seller.order.pending') }}"
                       class="{{ request()->routeIs('seller.order.pending') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Pending Orders</span>
                        @php
                            $pendingCount = \App\Models\SellerOrder::where('seller_id', auth('seller')->id())
                                ->where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="menu-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.order.confirmed') }}"
                       class="{{ request()->routeIs('seller.order.confirmed') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Confirmed Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.order.rescheduled') }}"
                       class="{{ request()->routeIs('seller.order.rescheduled') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>Rescheduled Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.order.completed') }}"
                       class="{{ request()->routeIs('seller.order.completed') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Completed Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.order.returned') }}"
                       class="{{ request()->routeIs('seller.order.returned') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7v6a9 9 0 0 0 9 9 9 9 0 0 0 9-9V7"/>
                            <polyline points="9 10 12 13 15 10"/><line x1="12" y1="13" x2="12" y2="3"/>
                        </svg>
                        <span>Returned Orders</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    {{-- ═══ CALL MANAGEMENT ═══ --}}
    <li class="menu-section call-management-section" style="color:#8b5cf6">Call Management</li>
    <ul>
        <li class="has-submenu">
            <a href="{{ route('seller.call.pending') }}"
               class="{{ request()->routeIs('seller.call.*') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12" y2="18"></line>
                </svg>
                <span>Calls</span>
                <span class="menu-badge-emerald">NEW</span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('seller.call.pending') }}"
                       class="{{ request()->routeIs('seller.call.pending') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Pending Calls</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.call.called') }}"
                       class="{{ request()->routeIs('seller.call.called') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                            <line x1="12" y1="18" x2="12" y2="18"/>
                        </svg>
                        <span>Called</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.call.confirmed') }}"
                       class="{{ request()->routeIs('seller.call.confirmed') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Confirmed</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.call.not-answered') }}"
                       class="{{ request()->routeIs('seller.call.not-answered') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="12" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>Not Answered</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.call.cancelled') }}"
                       class="{{ request()->routeIs('seller.call.cancelled') ? 'active' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <span>Cancelled</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    {{-- ═══ SELF-SELLER DELIVERY MANAGEMENT (only for self_seller) ═══ --}}
    @if($isSelfSeller)
        <li class="menu-section delivery-section" style="color:#059669">Delivery Management</li>
        <ul>
            <li class="has-submenu">
                <a href="{{ route('seller.delivery_calls.pending') }}"
                   class="{{ request()->routeIs('seller.delivery_calls.*') ? 'active' : '' }}">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13" rx="2"></rect>
                        <path d="M16 8h4l3 3v5h-7V8z"></path>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                    <span>Delivery Calls</span>
                    @php
                        $pendingDeliveryCount = \App\Models\SelfSellerDeliveryCall::where('seller_id', auth('seller')->id())
                            ->where('status', 'pending')->count();
                    @endphp
                    @if($pendingDeliveryCount > 0)
                        <span class="menu-badge-emerald">{{ $pendingDeliveryCount }}</span>
                    @endif
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('seller.delivery_calls.pending') }}"
                           class="{{ request()->routeIs('seller.delivery_calls.pending') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>Pending</span>
                            @php
                                $pendingCount = \App\Models\SelfSellerDeliveryCall::where('seller_id', auth('seller')->id())
                                    ->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="menu-badge">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.delivery_calls.packing') }}"
                           class="{{ request()->routeIs('seller.delivery_calls.packing') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                            <span>Packing</span>
                            @php
                                $packingCount = \App\Models\SelfSellerDeliveryCall::where('seller_id', auth('seller')->id())
                                    ->where('status', 'packing')->count();
                            @endphp
                            @if($packingCount > 0)
                                <span class="menu-badge-blue">{{ $packingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.delivery_calls.packed') }}"
                           class="{{ request()->routeIs('seller.delivery_calls.packed') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Packed</span>
                            @php
                                $packedCount = \App\Models\SelfSellerDeliveryCall::where('seller_id', auth('seller')->id())
                                    ->where('status', 'packed')->count();
                            @endphp
                            @if($packedCount > 0)
                                <span class="menu-badge">{{ $packedCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.delivery_calls.givedriver') }}"
                           class="{{ request()->routeIs('seller.delivery_calls.givedriver') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                            <span>Give Driver</span>
                            @php
                                $givedriverCount = \App\Models\SelfSellerDeliveryCall::where('seller_id', auth('seller')->id())
                                    ->where('status', 'givedriver')->count();
                            @endphp
                            @if($givedriverCount > 0)
                                <span class="menu-badge-emerald">{{ $givedriverCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.delivery_calls.create') }}"
                           class="{{ request()->routeIs('seller.delivery_calls.create') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Add New Call</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    @endif

    {{-- ═══ CREATE NEW ORDER ═══ --}}
    <li class="menu-section" style="color:#10b981">Create New</li>
    <ul>
        <li>
            <a href="{{ route('seller.create.new-order') }}"
               class="{{ request()->routeIs('seller.create.new-order') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                <span>New Order</span>
                <span class="menu-badge-blue">Single</span>
            </a>
        </li>
        <li>
            <a href="{{ route('seller.create.new-order') }}?bulk=1"
               class="{{ request()->get('bulk') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>Bulk Upload CSV</span>
                <span class="menu-badge" style="background:#10b981;">CSV</span>
            </a>
        </li>
    </ul>

    {{-- ═══ PRODUCT MANAGEMENT (not for system_seller) ═══ --}}
    @if(!$isSystemSeller)
        <li class="menu-section product-management-section" style="color:#f97316">Product Management</li>
        <ul>
            <li class="has-submenu">
                <a href="{{ route('seller.product.index') }}"
                   class="{{ request()->routeIs('seller.product.*') ? 'active' : '' }}">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>Products</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('seller.product.index') }}"
                           class="{{ request()->routeIs('seller.product.index') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 21 6 21 18 3 18"></polyline><line x1="9" y1="6" x2="9" y2="18"></line>
                            </svg>
                            <span>All Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.product.create') }}"
                           class="{{ request()->routeIs('seller.product.create') ? 'active' : '' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            <span>Add New Product</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('seller.brands.index') }}"
                   class="{{ request()->routeIs('seller.brands.*') ? 'active' : '' }}">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                    <span>Brands</span>
                </a>
            </li>

            <li>
                <a href="{{ route('seller.category.index') }}"
                   class="{{ request()->routeIs('seller.category.*') ? 'active' : '' }}">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <span>Categories</span>
                </a>
            </li>
        </ul>
    @endif

    {{-- ═══ PAYMENT MANAGEMENT ═══ --}}
    <li class="menu-section">Payment</li>
    <ul>
        <li>
            <a href="{{ route('seller.payment.my-payment') }}"
               class="{{ request()->routeIs('seller.payment.my-payment') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                <span>My Payment</span>
            </a>
        </li>
        <li>
            <a href="{{ route('seller.payment.withdraw') }}"
               class="{{ request()->routeIs('seller.payment.withdraw') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
                <span>Withdraw</span>
            </a>
        </li>
        <li>
            <a href="{{ route('seller.payment.history') }}"
               class="{{ request()->routeIs('seller.payment.history') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                </svg>
                <span>Payment History</span>
            </a>
        </li>
    </ul>

    {{-- ═══ ACCOUNT ═══ --}}
    <li class="menu-section">Account</li>
    <ul>
        <li>
            <a href="{{ route('seller.profile.index') }}"
               class="{{ request()->routeIs('seller.profile.*') ? 'active' : '' }}">
                <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>My Profile</span>
            </a>
        </li>
        <li>
            <form action="{{ route('seller.logout') }}" method="POST" id="seller-logout-form">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('seller-logout-form').submit();">
                    <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </a>
            </form>
        </li>
    </ul>
</ul>
