@extends('seller.layout.app')

@push('title')
    Pending Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --primary: #4f46e5; --primary-dark: #4338ca;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0; --surface: #ffffff; --bg: #f8fafc;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .page-header {
            background: linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(59,130,246,.2);
        }
        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: .5rem;
        }
        .order-count {
            background: rgba(255,255,255,.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        /* Navigation Tabs */
        .nav-tabs {
            border: none;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .nav-tabs .nav-link {
            border: none;
            font-weight: 600;
            color: var(--mid);
            padding: 12px 24px;
            border-radius: 8px;
            margin-right: 4px;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }
        .nav-tabs .nav-link.active {
            background: var(--primary);
            color: white;
        }
        .nav-tabs .nav-link .badge {
            margin-left: 8px;
            background: var(--danger);
            color: white;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            border-left: 4px solid transparent;
        }
        .stat-card.pending { border-left-color: var(--warning); }
        .stat-card.amount { border-left-color: var(--primary); }
        .stat-card.products { border-left-color: var(--info); }
        .stat-card.customers { border-left-color: var(--success); }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        .filter-group {
            flex: 1;
            min-width: 180px;
        }
        .filter-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--mid);
            margin-bottom: 4px;
            display: block;
        }
        .filter-select {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--dark);
            background: white;
            cursor: pointer;
        }
        .filter-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        /* Active filter highlight */
        .filter-select.filter-active {
            border-color: var(--primary);
            background: rgba(79,70,229,.04);
            color: var(--primary);
            font-weight: 600;
        }
        .clear-filters {
            background: var(--bg);
            color: var(--mid);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--border);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Call status filter pill row */
        .call-filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 14px;
            margin-bottom: 1.5rem;
        }
        .call-filter-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            margin-right: 4px;
            white-space: nowrap;
        }
        .call-filter-pill {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all .2s;
            background: white;
            color: #475569;
            border-color: #e2e8f0;
        }
        .call-filter-pill:hover { border-color: #cbd5e1; color: #334155; }
        .call-filter-pill.active { border-color: currentColor; }
        .pill-all        { color: #4f46e5; }
        .pill-all.active { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }
        .pill-not-confirmed        { color: #dc2626; }
        .pill-not-confirmed.active { background: #fee2e2; border-color: #fca5a5; color: #b91c1c; }
        .pill-pending-call        { color: #92400e; }
        .pill-pending-call.active { background: #fef3c7; border-color: #fde68a; color: #92400e; }
        .pill-called        { color: #1e40af; }
        .pill-called.active { background: #dbeafe; border-color: #93c5fd; color: #1e40af; }
        .pill-confirmed        { color: #065f46; }
        .pill-confirmed.active { background: #d1fae5; border-color: #6ee7b7; color: #065f46; }
        .pill-not-answered        { color: #475569; }
        .pill-not-answered.active { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }
        .pill-cancelled        { color: #991b1b; }
        .pill-cancelled.active { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }

        .call-filter-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,.07);
            border-radius: 10px;
            padding: 1px 6px;
            font-size: 11px;
            margin-left: 4px;
        }

        /* Bulk Actions */
        .bulk-actions {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid var(--primary);
            box-shadow: 0 4px 12px rgba(79,70,229,.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            animation: slideDown 0.3s ease-out;
        }
        .selected-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }
        .bulk-status-select {
            padding: 8px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            min-width: 150px;
        }
        .bulk-apply-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .bulk-apply-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Table Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
        }
        .table-responsive {
            border-radius: 0 0 16px 16px;
            overflow-x: auto;
        }
        .table {
            margin: 0;
            border-collapse: collapse;
            width: 100%;
        }
        .table thead th {
            background: var(--bg);
            border-bottom: 2px solid var(--border);
            padding: 1rem;
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }
        .table tbody tr:hover {
            background-color: rgba(79,70,229,.03);
        }

        /* Product Cell */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--border);
        }
        .product-details {
            flex: 1;
            min-width: 0;
        }
        .product-name {
            font-weight: 600;
            color: var(--dark);
            margin: 0 0 4px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-code {
            font-size: 12px;
            color: var(--mid);
            font-family: monospace;
            background: var(--bg);
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Badges */
        .badge-platform {
            background: rgba(79,70,229,.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-call-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-call-status.pending-call { background:#fef3c7; color:#92400e; }
        .badge-call-status.called { background:#dbeafe; color:#1e40af; }
        .badge-call-status.confirmed { background:#d1fae5; color:#065f46; }
        .badge-call-status.not-answered { background:#f1f5f9; color:#475569; }
        .badge-call-status.cancelled { background:#fee2e2; color:#991b1b; }

        /* Status Selector */
        .status-select {
            width: 140px;
            padding: 6px 10px;
            border: 2px solid var(--border);
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            background: white;
            cursor: pointer;
            transition: all .2s;
        }
        .status-select:hover {
            border-color: var(--primary);
        }
        .status-select.status-pending { border-color: #f59e0b; background: #fef3c7; color: #92400e; }
        .status-select.status-complete { border-color: #10b981; background: #d1fae5; color: #065f46; }
        .status-select.status-return { border-color: #ef4444; background: #fee2e2; color: #991b1b; }
        .status-select.status-rescheduling { border-color: #eab308; background: #fef9c3; color: #854d0e; }
        .status-select.status-cancelled { border-color: #64748b; background: #f1f5f9; color: #475569; }

        /* Checkbox */
        .order-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* Action Buttons */
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            cursor: pointer;
        }
        .action-btn.view   { background: var(--primary); color: white; }
        .action-btn.edit   { background: var(--warning);  color: white; }
        .action-btn.delete { background: var(--danger);   color: white; }
        .action-btn:hover  { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }

        /* Search */
        .search-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }
        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }
        .search-input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mid);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-icon {
            font-size: 64px;
            color: var(--border);
            margin-bottom: 16px;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        .modal-header {
            background: var(--primary);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .modal-body {
            padding: 2rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid var(--border);
            padding: .75rem 1rem;
        }

        /* Toast */
        .status-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--dark);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media(max-width:768px){
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .page-header { padding: 1.5rem; }
            .page-header h1 { font-size: 22px; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
            .call-filter-bar { gap: 6px; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="vertical-align: middle; font-size: 32px;">pending_actions</span>
                        Pending Orders
                    </h1>
                    <p class="mb-0 opacity-75">Manage and track your pending orders</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="order-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">shopping_bag</span>
                        <span id="totalOrdersCount">{{ $count }}</span> {{ $count === 1 ? 'Order' : 'Orders' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $totalAmount = $orders->sum('total_amount');
            $totalQuantity = $orders->sum('quantity');
            $uniqueCustomers = $orders->pluck('customer_phone')->unique()->count();
        @endphp
        <div class="stats-grid">
            <div class="stat-card pending">
                <h6 class="text-muted mb-2">Pending Orders</h6>
                <h2 class="fw-bold mb-0">{{ $count }}</h2>
                <small class="text-muted">Awaiting processing</small>
            </div>
            <div class="stat-card amount">
                <h6 class="text-muted mb-2">Total Amount</h6>
                <h2 class="fw-bold mb-0">Rs. {{ number_format($totalAmount, 2) }}</h2>
                <small class="text-muted">Value of pending orders</small>
            </div>
            <div class="stat-card products">
                <h6 class="text-muted mb-2">Total Items</h6>
                <h2 class="fw-bold mb-0">{{ $totalQuantity }}</h2>
                <small class="text-muted">Units to be delivered</small>
            </div>
            <div class="stat-card customers">
                <h6 class="text-muted mb-2">Customers</h6>
                <h2 class="fw-bold mb-0">{{ $uniqueCustomers }}</h2>
                <small class="text-muted">Unique customers</small>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a href="{{ route('seller.order.pending') }}" class="nav-link {{ request()->routeIs('seller.order.pending') ? 'active' : '' }}">
                    <span class="material-icons-round me-2" style="font-size: 18px;">pending_actions</span>
                    Pending
                    @php $pendingCount = \App\Models\SellerOrder::where('seller_id', auth('seller')->id())->where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.completed') }}" class="nav-link">
                    <span class="material-icons-round me-2" style="font-size: 18px;">check_circle</span>
                    Completed
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.returned') }}" class="nav-link">
                    <span class="material-icons-round me-2" style="font-size: 18px;">assignment_return</span>
                    Return
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.rescheduled') }}" class="nav-link">
                    <span class="material-icons-round me-2" style="font-size: 18px;">event_repeat</span>
                    Rescheduling
                </a>
            </li>
        </ul>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round">error</span>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <span class="filter-label">Filter by Platform</span>
                <select id="platformFilter" class="filter-select">
                    <option value="all">All Platforms</option>
                    <option value="fb">Facebook</option>
                    <option value="ig">Instagram</option>
                    <option value="tiktok">TikTok</option>
                    <option value="website">Website</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div>
                <button class="clear-filters" onclick="clearAllFilters()">
                    <span class="material-icons-round">clear</span>
                    Clear Filters
                </button>
            </div>
        </div>

        {{-- ── Call Status Filter Pills ────────────────────────────────── --}}
        @if($count > 0)
            <div class="call-filter-bar" id="callFilterBar">
            <span class="call-filter-label">
                <span class="material-icons-round" style="font-size:14px;vertical-align:middle;">call</span>
                Call Status:
            </span>

                {{-- Count per call status from PHP so pills show correct numbers --}}
                @php
                    $csGroups = $orders->groupBy(function($o) {
                        return strtolower(str_replace(' ', '-', $o->call_status ?? 'pending-call'));
                    });
                @endphp

                <button class="call-filter-pill pill-not-confirmed active"
                        data-call-filter="not-confirmed"
                        onclick="setCallFilter('not-confirmed', this)">
                    🚫 Not Confirmed
                    <span class="call-filter-count" id="cnt-not-confirmed">
                    {{ $orders->filter(fn($o) => strtolower($o->call_status ?? '') !== 'confirmed')->count() }}
                </span>
                </button>

                <button class="call-filter-pill pill-all"
                        data-call-filter="all"
                        onclick="setCallFilter('all', this)">
                    All
                    <span class="call-filter-count" id="cnt-all">{{ $count }}</span>
                </button>

                <button class="call-filter-pill pill-pending-call"
                        data-call-filter="pending-call"
                        onclick="setCallFilter('pending-call', this)">
                    ⏳ Pending Call
                    <span class="call-filter-count" id="cnt-pending-call">
                    {{ $csGroups->get('pending-call', collect())->count() + $csGroups->get('pending call', collect())->count() }}
                </span>
                </button>

                <button class="call-filter-pill pill-called"
                        data-call-filter="called"
                        onclick="setCallFilter('called', this)">
                    📞 Called
                    <span class="call-filter-count" id="cnt-called">{{ $csGroups->get('called', collect())->count() }}</span>
                </button>

                <button class="call-filter-pill pill-not-answered"
                        data-call-filter="not-answered"
                        onclick="setCallFilter('not-answered', this)">
                    📵 Not Answered
                    <span class="call-filter-count" id="cnt-not-answered">{{ $csGroups->get('not-answered', collect())->count() }}</span>
                </button>

                <button class="call-filter-pill pill-confirmed"
                        data-call-filter="confirmed"
                        onclick="setCallFilter('confirmed', this)">
                    ✅ Confirmed
                    <span class="call-filter-count" id="cnt-confirmed">{{ $csGroups->get('confirmed', collect())->count() }}</span>
                </button>

                <button class="call-filter-pill pill-cancelled"
                        data-call-filter="cancelled"
                        onclick="setCallFilter('cancelled', this)">
                    ❌ Cancelled
                    <span class="call-filter-count" id="cnt-cancelled">{{ $csGroups->get('cancelled', collect())->count() }}</span>
                </button>
            </div>
        @endif

        <!-- Bulk Actions -->
        <div id="bulkActions" class="bulk-actions" style="display: none;">
            <div class="d-flex align-items-center gap-3">
                <span class="selected-count" id="selectedCount">0 selected</span>
                <label class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                    <span>Select All</span>
                </label>
            </div>
            <div class="d-flex gap-2">
                <select id="bulkStatusSelect" class="bulk-status-select">
                    <option value="">Change Status...</option>
                    <option value="pending">Pending</option>
                    <option value="complete">Complete</option>
                    <option value="return">Return</option>
                    <option value="rescheduling">Rescheduling</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button class="bulk-apply-btn" id="bulkApplyBtn" onclick="applyBulkUpdate()" disabled>
                    <span class="material-icons-round">check</span>
                    Apply
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">
                            <span class="material-icons-round me-2" style="vertical-align: middle;">list_alt</span>
                            Pending Orders List
                            <span id="filteredCount" class="badge bg-secondary ms-2">{{ $count }}</span>
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary">
                            <span class="material-icons-round me-2">add</span>
                            New Order
                        </a>
                    </div>
                </div>
            </div>

            @if($count > 0)
                <!-- Search -->
                <div class="px-4 pt-3">
                    <div class="search-wrapper">
                        <span class="material-icons-round search-icon">search</span>
                        <input type="text" id="searchInput" class="search-input" placeholder="Search orders...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="ordersTable">
                            <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="headerCheckbox" class="order-checkbox" onchange="toggleAllRows()">
                                </th>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Platform</th>
                                <th>Call Status</th>
                                <th>Order Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
                                <tr data-order-id="{{ $order->id }}"
                                    data-status="{{ $order->status ?? 'pending' }}"
                                    data-call-status="{{ strtolower(str_replace(' ', '-', $order->call_status ?? 'pending-call')) }}"
                                    data-platform="{{ $order->platform ?? 'manual' }}">
                                    <td>
                                        <input type="checkbox" class="order-checkbox row-checkbox" value="{{ $order->id }}" onchange="updateBulkActions()">
                                    </td>
                                    <td>
                                        <strong style="font-family: monospace;">{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div class="product-cell">
                                            @if($order->product_image)
                                                <img src="{{ asset('storage/' . $order->product_image) }}"
                                                     alt="{{ $order->product_name }}"
                                                     class="product-image"
                                                     onerror="this.style.display='none';">
                                            @endif
                                            <div class="product-details">
                                                <div class="product-name">{{ Str::limit($order->product_name, 30) }}</div>
                                                <span class="product-code">{{ $order->product_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                        @if($order->district)
                                            <br><small class="text-muted">{{ $order->district }}</small>
                                        @endif
                                    </td>
                                    <td><strong>{{ $order->quantity }}</strong></td>
                                    <td><strong class="text-danger">Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge-platform">{{ ucfirst($order->platform ?? 'Manual') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-call-status {{ strtolower(str_replace(' ', '-', $order->call_status ?? 'Pending Call')) }}">
                                            {{ $order->call_status ?? 'Pending Call' }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="status-select status-{{ $order->status ?? 'pending' }}"
                                                onchange="updateOrderStatus({{ $order->id }}, this.value)">
                                            <option value="pending" {{ ($order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="complete" {{ ($order->status ?? '') == 'complete' ? 'selected' : '' }}>✅ Complete</option>
                                            <option value="return" {{ ($order->status ?? '') == 'return' ? 'selected' : '' }}>↩️ Return</option>
                                            <option value="rescheduling" {{ ($order->status ?? '') == 'rescheduling' ? 'selected' : '' }}>📅 Rescheduling</option>
                                            <option value="cancelled" {{ ($order->status ?? '') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $order->id) }}"
                                               class="action-btn view" title="View Details">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }})"
                                                    class="action-btn edit" title="Edit Order">
                                                <span class="material-icons-round">edit</span>
                                            </button>
                                            <button onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')"
                                                    class="action-btn delete" title="Delete Order">
                                                <span class="material-icons-round">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card-body">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <span class="material-icons-round">inbox</span>
                        </div>
                        <h5>No Pending Orders</h5>
                        <p class="text-muted">You don't have any pending orders at the moment.</p>
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary mt-3">
                            <span class="material-icons-round me-2">add</span>
                            Create New Order
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2">edit</span>
                        Update Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order Status</label>
                            <select name="status" id="modalStatusSelect" class="form-select" required>
                                <option value="pending">⏳ Pending</option>
                                <option value="complete">✅ Complete</option>
                                <option value="return">↩️ Return</option>
                                <option value="rescheduling">📅 Rescheduling</option>
                                <option value="cancelled">❌ Cancelled</option>
                            </select>
                        </div>
                        <div id="deliveryDateField" class="mb-3" style="display:none;">
                            <label class="form-label fw-semibold">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div id="reschedulingDateField" class="mb-3" style="display:none;">
                            <label class="form-label fw-semibold">Rescheduling Date</label>
                            <input type="date" name="rescheduling_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div id="returnDateField" class="mb-3" style="display:none;">
                            <label class="form-label fw-semibold">Return Date</label>
                            <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark</label>
                            <textarea name="remark" class="form-control" rows="3" placeholder="Add notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="statusToast" class="status-toast" style="display: none;">
        <span class="material-icons-round" style="color: var(--success);">check_circle</span>
        <span id="toastMessage">Status updated</span>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const UPDATE_URL = (id) => `/seller/order/update/${id}`;

        // ── Active call status filter (default: hide confirmed orders) ──────
        let activeCallFilter = 'not-confirmed';

        // Store orders data
        let orders = [];
        @foreach($orders as $order)
        orders.push({
            id: {{ $order->id }},
            row: document.querySelector(`tr[data-order-id="{{ $order->id }}"]`),
            status: '{{ $order->status ?? "pending" }}',
            callStatus: '{{ strtolower(str_replace(' ', '-', $order->call_status ?? 'pending-call')) }}',
            platform: '{{ $order->platform ?? "manual" }}',
            text: `{{ $order->order_number }} {{ $order->product_name }} {{ $order->customer_name }} {{ $order->customer_phone }}`.toLowerCase()
        });
        @endforeach

        // ── Call Status Filter ────────────────────────────────────────────────
        function setCallFilter(filter, el) {
            activeCallFilter = filter;

            // Update pill active states
            document.querySelectorAll('.call-filter-pill').forEach(p => p.classList.remove('active'));
            if (el) el.classList.add('active');

            filterTable();
        }

        function callStatusMatches(orderCallStatus, filter) {
            if (filter === 'all')           return true;
            if (filter === 'not-confirmed') return orderCallStatus !== 'confirmed';
            return orderCallStatus === filter;
        }

        // ── Initialize DataTable + apply default filter ───────────────────────
        $(document).ready(function () {
            @if($count > 0)
            $('#ordersTable').DataTable({
                order: [[6, 'desc']],
                pageLength: 10,
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                paging: true,
                columnDefs: [
                    { orderable: false, targets: [0, 8, 9, 10] }
                ]
            });
            @endif

            // Modal status change handler
            $('#modalStatusSelect').on('change', function() {
                const status = this.value;
                $('#deliveryDateField, #reschedulingDateField, #returnDateField').hide();
                if (status === 'complete')      $('#deliveryDateField').show();
                else if (status === 'rescheduling') $('#reschedulingDateField').show();
                else if (status === 'return')   $('#returnDateField').show();
            });

            // Apply the default "not-confirmed" filter on page load
            filterTable();
        });

        // ── Core filter function ──────────────────────────────────────────────
        function filterTable() {
            const searchTerm     = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const platformFilter = document.getElementById('platformFilter')?.value || 'all';

            let visibleCount = 0;
            orders.forEach(order => {
                const row = order.row;
                if (!row) return;

                const matchesSearch   = searchTerm === '' || order.text.includes(searchTerm);
                const matchesPlatform = platformFilter === 'all' || order.platform === platformFilter;
                const matchesCall     = callStatusMatches(order.callStatus, activeCallFilter);

                if (matchesSearch && matchesPlatform && matchesCall) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                    const checkbox = row.querySelector('.row-checkbox');
                    if (checkbox) checkbox.checked = false;
                }
            });

            document.getElementById('filteredCount').textContent = visibleCount;
            document.getElementById('totalOrdersCount').textContent = visibleCount;
            updateBulkActions();
            updateHeaderCheckbox();
        }

        function clearAllFilters() {
            document.getElementById('platformFilter').value = 'all';
            document.getElementById('searchInput').value = '';
            // Reset call filter to "not-confirmed" (default)
            activeCallFilter = 'not-confirmed';
            document.querySelectorAll('.call-filter-pill').forEach(p => p.classList.remove('active'));
            const notConfirmedPill = document.querySelector('[data-call-filter="not-confirmed"]');
            if (notConfirmedPill) notConfirmedPill.classList.add('active');
            filterTable();
        }

        document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
        document.getElementById('platformFilter')?.addEventListener('change', filterTable);

        // ── Update Order Status ───────────────────────────────────────────────
        async function updateOrderStatus(orderId, newStatus) {
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;

            const selectEl = row.querySelector('select[onchange*="updateOrderStatus"]');
            const oldStatus = row.dataset.status || 'pending';

            try {
                selectEl.disabled = true;

                const form = new FormData();
                form.append('_token', CSRF_TOKEN);
                form.append('_method', 'PUT');
                form.append('status', newStatus);

                if (newStatus === 'complete')      form.append('delivery_date',     new Date().toISOString().split('T')[0]);
                else if (newStatus === 'return')    form.append('return_date',       new Date().toISOString().split('T')[0]);
                else if (newStatus === 'rescheduling') form.append('rescheduling_date', new Date().toISOString().split('T')[0]);

                const res = await fetch(UPDATE_URL(orderId), {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.message || 'Update failed');
                }

                row.dataset.status = newStatus;
                const o = orders.find(x => x.id === orderId);
                if (o) o.status = newStatus;

                showToast(`Order status updated to ${newStatus}`);

            } catch (e) {
                selectEl.value = oldStatus;
                Swal.fire({ icon: 'error', title: 'Update Failed', text: e.message });
            } finally {
                selectEl.disabled = false;
            }
        }

        // ── Bulk Actions ──────────────────────────────────────────────────────
        function getVisibleRowCheckboxes() {
            return Array.from(document.querySelectorAll('.row-checkbox'))
                .filter(cb => {
                    const tr = cb.closest('tr');
                    return tr && tr.style.display !== 'none';
                });
        }

        function getSelectedCheckboxes() {
            return Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .filter(cb => {
                    const tr = cb.closest('tr');
                    return tr && tr.style.display !== 'none';
                });
        }

        function updateBulkActions() {
            const selectedCount = getSelectedCheckboxes().length;
            const bulkActions   = document.getElementById('bulkActions');
            const bulkApplyBtn  = document.getElementById('bulkApplyBtn');
            const bulkStatus    = document.getElementById('bulkStatusSelect').value;

            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
                document.getElementById('selectedCount').textContent = `${selectedCount} selected`;
                bulkApplyBtn.disabled = !bulkStatus;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        document.getElementById('bulkStatusSelect')?.addEventListener('change', function() {
            const selectedCount = getSelectedCheckboxes().length;
            document.getElementById('bulkApplyBtn').disabled = !(selectedCount > 0 && this.value);
        });

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAllCheckbox').checked;
            getVisibleRowCheckboxes().forEach(cb => cb.checked = selectAll);
            updateBulkActions();
            updateHeaderCheckbox();
        }

        function toggleAllRows() {
            const headerCheckbox = document.getElementById('headerCheckbox').checked;
            getVisibleRowCheckboxes().forEach(cb => cb.checked = headerCheckbox);
            updateBulkActions();
            document.getElementById('selectAllCheckbox').checked = headerCheckbox;
        }

        function updateHeaderCheckbox() {
            const visibleCheckboxes = getVisibleRowCheckboxes();
            const checkedVisible    = visibleCheckboxes.filter(cb => cb.checked);
            const headerCheckbox    = document.getElementById('headerCheckbox');

            if (visibleCheckboxes.length > 0) {
                headerCheckbox.checked       = visibleCheckboxes.length === checkedVisible.length;
                headerCheckbox.indeterminate = checkedVisible.length > 0 && checkedVisible.length < visibleCheckboxes.length;
            } else {
                headerCheckbox.checked = false;
                headerCheckbox.indeterminate = false;
            }
        }

        async function applyBulkUpdate() {
            const selectedStatus = document.getElementById('bulkStatusSelect').value;
            if (!selectedStatus) {
                Swal.fire({ title: 'No Update Selected', text: 'Please select a status', icon: 'warning' });
                return;
            }

            const selectedCheckboxes = getSelectedCheckboxes();
            if (selectedCheckboxes.length === 0) return;

            const result = await Swal.fire({
                title: 'Update Multiple Orders?',
                html: `Update <strong>${selectedCheckboxes.length}</strong> orders to <strong>${selectedStatus}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update'
            });
            if (!result.isConfirmed) return;

            const bulkApplyBtn = document.getElementById('bulkApplyBtn');
            bulkApplyBtn.disabled = true;
            bulkApplyBtn.innerHTML = `<span class="material-icons-round">hourglass_top</span> Updating...`;

            const ids = selectedCheckboxes.map(cb => parseInt(cb.value));
            let okCount = 0, failCount = 0;

            for (const orderId of ids) {
                try {
                    const form = new FormData();
                    form.append('_token', CSRF_TOKEN);
                    form.append('_method', 'PUT');
                    form.append('status', selectedStatus);

                    if (selectedStatus === 'complete')       form.append('delivery_date',     new Date().toISOString().split('T')[0]);
                    else if (selectedStatus === 'return')    form.append('return_date',        new Date().toISOString().split('T')[0]);
                    else if (selectedStatus === 'rescheduling') form.append('rescheduling_date', new Date().toISOString().split('T')[0]);

                    const res = await fetch(UPDATE_URL(orderId), {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: form
                    });

                    if (res.ok) {
                        okCount++;
                        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                        if (row) {
                            row.dataset.status = selectedStatus;
                            const select = row.querySelector('select[onchange*="updateOrderStatus"]');
                            if (select) select.value = selectedStatus;
                            const o = orders.find(x => x.id === orderId);
                            if (o) o.status = selectedStatus;
                        }
                    } else {
                        failCount++;
                    }
                } catch { failCount++; }
            }

            selectedCheckboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
            document.getElementById('headerCheckbox').checked = false;
            document.getElementById('bulkStatusSelect').value = '';
            updateBulkActions();
            filterTable();

            bulkApplyBtn.innerHTML = `<span class="material-icons-round">check</span> Apply`;
            bulkApplyBtn.disabled = true;

            showToast(`Updated ${okCount} orders, ${failCount} failed`);
        }

        // ── Other ─────────────────────────────────────────────────────────────
        function editOrder(id) {
            window.location.href = `/seller/order/edit/${id}`;
        }

        function deleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/seller/order/destroy/${id}`;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = CSRF_TOKEN;
                    const method = document.createElement('input');
                    method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function showToast(message) {
            const toast = document.getElementById('statusToast');
            document.getElementById('toastMessage').textContent = message;
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => { toast.style.display = 'none'; toast.style.opacity = '1'; }, 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkActions();
                    updateHeaderCheckbox();
                });
            });
        });
    </script>
@endpush
