@extends('seller.layout.app')

@push('title')
    Pending Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5; --primary-dark: #4338ca;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0; --surface: #ffffff; --bg: #f8fafc;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .pending-header { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; padding:2rem; border-radius:16px; margin-bottom:2rem; box-shadow:0 8px 20px rgba(102,126,234,.2); }
        .pending-header h1 { font-size:28px; font-weight:800; margin-bottom:.5rem; }
        .order-count { background:rgba(255,255,255,.2); padding:8px 20px; border-radius:999px; display:inline-block; font-weight:600; }

        .stats-card { background:white; border-radius:12px; padding:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,.08); transition:all .3s; border-left:4px solid var(--primary); }
        .stats-card:hover { transform:translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,.12); }
        .stats-card.pending { border-left-color:var(--warning); }
        .stats-card.amount  { border-left-color:var(--primary); }
        .stats-card.recent  { border-left-color:var(--info); }
        .stats-icon { width:60px; height:60px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; font-size:28px; }
        .stats-icon.pending { background:rgba(245,158,11,.1); color:var(--warning); }
        .stats-icon.amount  { background:rgba(79,70,229,.1);  color:var(--primary); }
        .stats-icon.recent  { background:rgba(59,130,246,.1); color:var(--info);    }

        .nav-tabs { border:none; background:white; border-radius:12px; padding:8px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .nav-tabs .nav-link { border:none; font-weight:600; color:var(--mid); padding:12px 24px; border-radius:8px; margin-right:4px; transition:all .3s; }
        .nav-tabs .nav-link:hover  { background:rgba(79,70,229,.1); color:var(--primary); }
        .nav-tabs .nav-link.active { background:var(--primary); color:white; }

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
            transition: all .3s;
        }
        .filter-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .filter-badge {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: all .2s;
        }
        .filter-badge:hover {
            background: var(--primary-dark);
        }
        .filter-badge .material-icons-round {
            font-size: 14px;
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
            transition: all .2s;
        }
        .clear-filters:hover {
            background: var(--border);
            color: var(--dark);
        }
        .active-filter-count {
            background: var(--primary);
            color: white;
            border-radius: 30px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: 8px;
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
        .bulk-actions-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .selected-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }
        .bulk-select-all {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--dark);
            font-size: 14px;
            cursor: pointer;
        }
        .bulk-actions-right {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .bulk-status-select, .bulk-call-status-select {
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
            transition: all .2s;
        }
        .bulk-apply-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        .bulk-apply-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .product-cell { display:flex; align-items:center; gap:12px; }
        .product-image { width:50px; height:50px; border-radius:8px; object-fit:cover; border:2px solid var(--border); }
        .product-image-placeholder { width:50px; height:50px; border-radius:8px; background:var(--bg); border:2px dashed var(--border); display:flex; align-items:center; justify-content:center; color:var(--mid); }
        .product-details { flex:1; min-width:0; }
        .product-name { font-weight:600; color:var(--dark); margin:0 0 4px; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .product-code { font-size:12px; color:var(--mid); font-family:monospace; background:var(--bg); padding:2px 8px; border-radius:4px; }

        .card { border:none; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .card-header { background:white; border-bottom:1px solid var(--border); padding:1.5rem; }
        .table-responsive { border-radius:0 0 16px 16px; overflow:auto; }
        .table { margin:0; border-collapse:collapse; }
        .table thead th { background:var(--bg); border-bottom:2px solid var(--border); padding:1rem; font-weight:700; color:var(--dark); font-size:13px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
        .table tbody td { padding:1rem; vertical-align:middle; border-bottom:1px solid var(--border); }
        .table tbody tr:hover { background-color:rgba(79,70,229,.03); }

        .badge-pending      { background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; }
        .badge-platform     { background:rgba(79,70,229,.1);  color:var(--primary); padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }

        /* Status Badges */
        .badge-status { padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:4px; }
        .badge-status.pending      { background:#fef3c7; color:#92400e; }
        .badge-status.complete     { background:#d1fae5; color:#065f46; }
        .badge-status.return       { background:#fee2e2; color:#991b1b; }
        .badge-status.rescheduling { background:#fff3cd; color:#856404; }
        .badge-status.cancelled    { background:#f1f5f9; color:#475569; }

        /* Call Status Badges */
        .badge-call-status { padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:4px; }
        .badge-call-status.pending-call { background:#fef3c7; color:#92400e; }
        .badge-call-status.called       { background:#dbeafe; color:#1e40af; }
        .badge-call-status.confirmed    { background:#d1fae5; color:#065f46; }
        .badge-call-status.not-answered { background:#f1f5f9; color:#475569; }
        .badge-call-status.cancelled    { background:#fee2e2; color:#991b1b; }

        /* Quick Action Cells */
        .quick-action-cell {
            min-width: 180px;
        }
        .quick-select {
            width: 100%;
            padding: 6px 10px;
            border: 2px solid var(--border);
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            background: white;
            cursor: pointer;
            transition: all .2s;
        }
        .quick-select:hover {
            border-color: var(--primary);
        }
        .quick-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .quick-select.status-pending { border-color: #f59e0b; background: #fef3c7; color: #92400e; }
        .quick-select.status-complete { border-color: #10b981; background: #d1fae5; color: #065f46; }
        .quick-select.status-return { border-color: #ef4444; background: #fee2e2; color: #991b1b; }
        .quick-select.status-rescheduling { border-color: #eab308; background: #fef9c3; color: #854d0e; }
        .quick-select.status-cancelled { border-color: #64748b; background: #f1f5f9; color: #475569; }

        .quick-select.call-pending-call { border-color: #f59e0b; background: #fef3c7; color: #92400e; }
        .quick-select.call-called { border-color: #3b82f6; background: #dbeafe; color: #1e40af; }
        .quick-select.call-confirmed { border-color: #10b981; background: #d1fae5; color: #065f46; }
        .quick-select.call-not-answered { border-color: #64748b; background: #f1f5f9; color: #475569; }
        .quick-select.call-cancelled { border-color: #ef4444; background: #fee2e2; color: #991b1b; }

        /* Checkbox Styles */
        .order-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .action-btn { width:36px; height:36px; border-radius:8px; border:none; display:inline-flex; align-items:center; justify-content:center; transition:all .2s; cursor:pointer; }
        .action-btn.view   { background:var(--primary); color:white; }
        .action-btn.edit   { background:var(--warning);  color:white; }
        .action-btn.delete { background:var(--danger);   color:white; }
        .action-btn:hover  { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }

        .alert { border-radius:12px; padding:1rem 1.25rem; border:none; display:flex; align-items:center; gap:12px; animation:slideDown .3s ease-out; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success { background:#d1fae5; color:#065f46; }
        .alert-danger  { background:#fee2e2; color:#991b1b; }
        .alert-info    { background:#dbeafe; color:#1e40af; }

        .modal-content { border-radius:16px; border:none; box-shadow:0 20px 50px rgba(0,0,0,.15); }
        .modal-header  { background:var(--primary); color:white; border-radius:16px 16px 0 0; padding:1.5rem; border-bottom:none; }
        .modal-body    { padding:2rem; }
        .form-control, .form-select { border-radius:8px; border:2px solid var(--border); padding:.75rem 1rem; transition:all .3s; }
        .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }

        .btn { padding:10px 20px; border-radius:8px; font-weight:600; transition:all .2s; border:none; cursor:pointer; }
        .btn-primary   { background:var(--primary); color:white; }
        .btn-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }
        .btn-secondary { background:var(--mid); color:white; }
        .btn-success   { background:var(--success); color:white; }

        .search-wrapper { position:relative; display:inline-block; width:100%; max-width:400px; }
        .search-input   { width:100%; padding:10px 16px 10px 40px; border:2px solid var(--border); border-radius:8px; font-size:14px; transition:all .3s; }
        .search-input:focus { border-color:var(--primary); outline:none; box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--mid); }

        .empty-state { text-align:center; padding:60px 20px; }
        .empty-icon  { font-size:64px; color:var(--border); margin-bottom:16px; }

        /* Quick Status Update Toast */
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
            .pending-header { padding:1.5rem; }
            .pending-header h1 { font-size:22px; }
            .nav-tabs .nav-link { padding:10px 16px; font-size:13px; }
            .action-btn { width:32px; height:32px; }
            .table { font-size:13px; }
            .table thead th, .table tbody td { padding:.75rem .5rem; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
            .bulk-actions { flex-direction: column; align-items: flex-start; }
            .quick-action-cell { min-width: 140px; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header -->
        <div class="pending-header">
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

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round">error</span>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Your existing stats cards -->
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-4">
            <!-- Your existing tabs -->
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <span class="filter-label">Filter by Status</span>
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="complete">Complete</option>
                    <option value="return">Return</option>
                    <option value="rescheduling">Rescheduling</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Filter by Call Status</span>
                <select id="callStatusFilter" class="filter-select">
                    <option value="all">All Call Statuses</option>
                    <option value="Pending Call">Pending Call</option>
                    <option value="Called">Called</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Not Answered">Not Answered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Filter by Platform</span>
                <select id="platformFilter" class="filter-select">
                    <option value="all">All Platforms</option>
                    <option value="Manual">Manual</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Website">Website</option>
                </select>
            </div>
            <div>
                <button class="clear-filters" onclick="clearAllFilters()">
                    <span class="material-icons-round" style="font-size: 16px;">clear</span>
                    Clear Filters
                    <span id="activeFilterCount" class="active-filter-count" style="display: none;">0</span>
                </button>
            </div>
        </div>

        <!-- Bulk Actions (hidden by default) -->
        <div id="bulkActions" class="bulk-actions" style="display: none;">
            <div class="bulk-actions-left">
                <span class="selected-count" id="selectedCount">0 selected</span>
                <label class="bulk-select-all">
                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                    <span>Select All</span>
                </label>
            </div>
            <div class="bulk-actions-right">
                <select id="bulkStatusSelect" class="bulk-status-select">
                    <option value="">Change Status...</option>
                    <option value="pending">Pending</option>
                    <option value="complete">Complete</option>
                    <option value="return">Return</option>
                    <option value="rescheduling">Rescheduling</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="bulkCallStatusSelect" class="bulk-call-status-select">
                    <option value="">Change Call Status...</option>
                    <option value="Pending Call">Pending Call</option>
                    <option value="Called">Called</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Not Answered">Not Answered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button class="bulk-apply-btn" id="bulkApplyBtn" onclick="applyBulkUpdate()" disabled>
                    <span class="material-icons-round" style="font-size: 18px;">check</span>
                    Apply to Selected
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
                            Orders List
                            <span id="filteredCount" class="badge bg-secondary ms-2" style="font-size: 12px;">{{ $count }}</span>
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('seller.new-order') }}" class="btn btn-primary">
                            <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">add</span>
                            New Order
                        </a>
                    </div>
                </div>
            </div>

            @if($count > 0)
                <!-- Search Box -->
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
                                <th>Status</th>
                                <th>Call Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
                                <tr data-order-id="{{ $order->id }}"
                                    data-status="{{ $order->status ?? 'pending' }}"
                                    data-call-status="{{ $order->call_status ?? 'Pending Call' }}"
                                    data-platform="{{ $order->platform ?? 'Manual' }}">
                                    <td>
                                        <input type="checkbox" class="order-checkbox row-checkbox" value="{{ $order->id }}" onchange="updateBulkActions()">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-primary me-2">receipt</span>
                                            <strong style="font-family: monospace;">{{ $order->order_number }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-cell">
                                            @if($order->product_image)
                                                <img src="{{ asset('storage/' . $order->product_image) }}"
                                                     alt="{{ $order->product_name }}"
                                                     class="product-image"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="product-image-placeholder" style="display: none;">
                                                    <span class="material-icons-round" style="font-size: 24px;">inventory_2</span>
                                                </div>
                                            @else
                                                <div class="product-image-placeholder">
                                                    <span class="material-icons-round" style="font-size: 24px;">inventory_2</span>
                                                </div>
                                            @endif
                                            <div class="product-details">
                                                <div class="product-name" title="{{ $order->product_name }}">
                                                    {{ Str::limit($order->product_name, 30) }}
                                                </div>
                                                <span class="product-code">{{ $order->product_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                                            <small class="text-muted">
                                                <span class="material-icons-round" style="font-size: 14px; vertical-align: middle;">phone</span>
                                                {{ $order->customer_phone }}
                                            </small>
                                            @if($order->district)
                                                <br>
                                                <small class="text-muted">
                                                    <span class="material-icons-round" style="font-size: 14px; vertical-align: middle;">location_on</span>
                                                    {{ $order->district }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-info me-2">inventory</span>
                                            <strong>{{ $order->quantity }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold text-danger">Rs. {{ number_format($order->total_amount, 2) }}</div>
                                            <small class="text-muted">@ Rs. {{ number_format($order->product_price, 2) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-secondary me-2">event</span>
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-platform">
                                            {{ $order->platform ?? 'Manual' }}
                                        </span>
                                    </td>
                                    <td class="quick-action-cell">
                                        <select class="quick-select status-{{ $order->status ?? 'pending' }}"
                                                onchange="quickStatusChange({{ $order->id }}, this.value, 'status')"
                                                data-order-id="{{ $order->id }}">
                                            <option value="pending" {{ ($order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="complete" {{ ($order->status ?? '') == 'complete' ? 'selected' : '' }}>✅ Complete</option>
                                            <option value="return" {{ ($order->status ?? '') == 'return' ? 'selected' : '' }}>↩️ Return</option>
                                            <option value="rescheduling" {{ ($order->status ?? '') == 'rescheduling' ? 'selected' : '' }}>📅 Rescheduling</option>
                                            <option value="cancelled" {{ ($order->status ?? '') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="quick-action-cell">
                                        <select class="quick-select call-{{ strtolower(str_replace(' ', '-', $order->call_status ?? 'Pending Call')) }}"
                                                onchange="quickStatusChange({{ $order->id }}, this.value, 'call_status')"
                                                data-order-id="{{ $order->id }}">
                                            <option value="Pending Call" {{ ($order->call_status ?? 'Pending Call') == 'Pending Call' ? 'selected' : '' }}>⏳ Pending Call</option>
                                            <option value="Called" {{ ($order->call_status ?? '') == 'Called' ? 'selected' : '' }}>📞 Called</option>
                                            <option value="Confirmed" {{ ($order->call_status ?? '') == 'Confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                                            <option value="Not Answered" {{ ($order->call_status ?? '') == 'Not Answered' ? 'selected' : '' }}>❓ Not Answered</option>
                                            <option value="Cancelled" {{ ($order->call_status ?? '') == 'Cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $order->id) }}"
                                               class="action-btn view"
                                               title="View Details">
                                                <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }})"
                                                    class="action-btn edit"
                                                    title="Update Status">
                                                <span class="material-icons-round" style="font-size: 18px;">edit</span>
                                            </button>
                                            <button onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')"
                                                    class="action-btn delete"
                                                    title="Delete Order">
                                                <span class="material-icons-round" style="font-size: 18px;">delete</span>
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
                            <span class="material-icons-round" style="font-size: inherit;">inbox</span>
                        </div>
                        <h5>No Pending Orders</h5>
                        <p class="text-muted">You don't have any pending orders at the moment.</p>
                        <a href="{{ route('seller.new-order') }}" class="btn btn-primary mt-3">
                            <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">add</span>
                            Create New Order
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">edit</span>
                        Update Order Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                                <option value="return">Return</option>
                                <option value="rescheduling">Rescheduling</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Call Status</label>
                            <select name="call_status" class="form-select">
                                <option value="Pending Call">Pending Call</option>
                                <option value="Called">Called</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Not Answered">Not Answered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3" id="deliveryDateField" style="display:none;">
                            <label class="form-label fw-semibold">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3" id="reschedulingDateField" style="display:none;">
                            <label class="form-label fw-semibold">Rescheduling Date</label>
                            <input type="date" name="rescheduling_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3" id="returnDateField" style="display:none;">
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

    <!-- Quick Status Update Toast (hidden by default) -->
    <div id="statusToast" class="status-toast" style="display: none;">
        <span class="material-icons-round" style="color: var(--success);">check_circle</span>
        <span id="toastMessage">Status updated successfully</span>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // ─────────────────────────────────────────────────────────────
        // IMPORTANT: USING EXISTING ROUTE (NO ROUTE CHANGES)
        // We will call: PUT /seller/order/update/{id}
        // Bulk update will call the SAME route per order (Promise.all)
        // ─────────────────────────────────────────────────────────────

        const CSRF_TOKEN = '{{ csrf_token() }}';
        const UPDATE_URL = (id) => `/seller/order/update/${id}`;

        // Store original orders data
        let orders = [];
        @foreach($orders as $order)
        orders.push({
            id: {{ $order->id }},
            row: document.querySelector(`tr[data-order-id="{{ $order->id }}"]`),
            status: '{{ $order->status ?? "pending" }}',
            callStatus: '{{ $order->call_status ?? "Pending Call" }}',
            platform: '{{ $order->platform ?? "Manual" }}',
            text: `{{ $order->order_number }} {{ $order->product_name }} {{ $order->customer_name }} {{ $order->customer_phone }}`.toLowerCase()
        });
        @endforeach

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            filterTable();
        });

        // Filter functionality
        document.getElementById('statusFilter')?.addEventListener('change', filterTable);
        document.getElementById('callStatusFilter')?.addEventListener('change', filterTable);
        document.getElementById('platformFilter')?.addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || 'all';
            const callStatusFilter = document.getElementById('callStatusFilter')?.value || 'all';
            const platformFilter = document.getElementById('platformFilter')?.value || 'all';

            let visibleCount = 0;
            let activeFilters = 0;

            if (statusFilter !== 'all') activeFilters++;
            if (callStatusFilter !== 'all') activeFilters++;
            if (platformFilter !== 'all') activeFilters++;
            if (searchTerm) activeFilters++;

            const countBadge = document.getElementById('activeFilterCount');
            if (activeFilters > 0) {
                countBadge.textContent = activeFilters;
                countBadge.style.display = 'inline-block';
            } else {
                countBadge.style.display = 'none';
            }

            orders.forEach(order => {
                const row = order.row;
                if (!row) return;

                const matchesSearch = searchTerm === '' || order.text.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || order.status === statusFilter;
                const matchesCallStatus = callStatusFilter === 'all' || order.callStatus === callStatusFilter;
                const matchesPlatform = platformFilter === 'all' || order.platform === platformFilter;

                if (matchesSearch && matchesStatus && matchesCallStatus && matchesPlatform) {
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
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('callStatusFilter').value = 'all';
            document.getElementById('platformFilter').value = 'all';
            document.getElementById('searchInput').value = '';
            filterTable();
        }

        // ─────────────────────────────────────────────────────────────
        // Helpers: visible rows / selections
        // ─────────────────────────────────────────────────────────────
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

        function setSelectLoading(selectEl, isLoading) {
            if (!selectEl) return;
            selectEl.disabled = isLoading;
            selectEl.style.opacity = isLoading ? '0.6' : '1';
        }

        // ─────────────────────────────────────────────────────────────
        // AJAX update using EXISTING ROUTE: PUT /seller/order/update/{id}
        // NOTE: If your controller expects form-data, this is OK.
        // We send _method=PUT + CSRF, as Laravel expects.
        // ─────────────────────────────────────────────────────────────
        async function apiUpdateOrder(orderId, payload) {
            const form = new FormData();
            form.append('_token', CSRF_TOKEN);
            form.append('_method', 'PUT');

            Object.keys(payload).forEach(k => {
                if (payload[k] !== undefined && payload[k] !== null && payload[k] !== '') {
                    form.append(k, payload[k]);
                }
            });

            const res = await fetch(UPDATE_URL(orderId), {
                method: 'POST', // method spoofing
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: form
            });

            // If controller returns JSON, great. If it redirects, we still handle.
            const contentType = res.headers.get('content-type') || '';
            let data = null;

            if (contentType.includes('application/json')) {
                data = await res.json();
            } else {
                // Try read as text (could be HTML redirect response)
                const text = await res.text();
                data = { ok: res.ok, raw: text };
            }

            if (!res.ok) {
                // Try get message
                const msg = (data && (data.message || data.error)) ? (data.message || data.error) : 'Update failed';
                throw new Error(msg);
            }

            return data;
        }

        // ─────────────────────────────────────────────────────────────
        // UI apply (after successful save)
        // ─────────────────────────────────────────────────────────────
        function applyUiUpdate(orderId, type, newValue) {
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;

            if (type === 'status') {
                const statusSelect = row.querySelector('select[onchange*="status"]');
                if (statusSelect) {
                    statusSelect.value = newValue;
                    statusSelect.className = `quick-select status-${newValue}`;
                }

                row.dataset.status = newValue;

                const o = orders.find(x => x.id === orderId);
                if (o) o.status = newValue;

            } else if (type === 'call_status') {
                const callSelect = row.querySelector('select[onchange*="call_status"]');
                const className = String(newValue).toLowerCase().replace(/\s+/g, '-');

                if (callSelect) {
                    callSelect.value = newValue;
                    callSelect.className = `quick-select call-${className}`;
                }

                row.dataset.callStatus = newValue;

                const o = orders.find(x => x.id === orderId);
                if (o) o.callStatus = newValue;
            }
        }

        // ─────────────────────────────────────────────────────────────
        // Quick status change (NOW SAVES TO DB via existing route)
        // ─────────────────────────────────────────────────────────────
        async function quickStatusChange(orderId, newValue, type) {
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;

            // Which select is changing?
            const selectEl = (type === 'status')
                ? row.querySelector('select[onchange*="status"]')
                : row.querySelector('select[onchange*="call_status"]');

            // Remember old value to rollback if error
            const oldValue = (type === 'status')
                ? (row.dataset.status || 'pending')
                : (row.dataset.callStatus || 'Pending Call');

            try {
                setSelectLoading(selectEl, true);

                // Build payload for backend
                const payload = {};
                payload[type] = newValue;

                // Optional fields (only if your backend expects them)
                // If you want to auto-set dates on complete/return/rescheduling, you can send here.
                // Example:
                // if (type === 'status' && newValue === 'complete') payload.delivery_date = new Date().toISOString().slice(0,10);

                await apiUpdateOrder(orderId, payload);

                // Success: update UI + local orders array
                applyUiUpdate(orderId, type, newValue);

                showToast(`Order #${orderId} ${type === 'status' ? 'status' : 'call status'} updated to ${newValue}`);

                // Re-filter
                filterTable();

            } catch (e) {
                // Rollback UI
                applyUiUpdate(orderId, type, oldValue);

                Swal.fire({
                    title: 'Update Failed',
                    text: e.message || 'Something went wrong',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });

            } finally {
                setSelectLoading(selectEl, false);
            }
        }

        // ─────────────────────────────────────────────────────────────
        // Bulk actions
        // ─────────────────────────────────────────────────────────────
        function updateBulkActions() {
            const selectedCount = getSelectedCheckboxes().length;
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountSpan = document.getElementById('selectedCount');
            const bulkApplyBtn = document.getElementById('bulkApplyBtn');
            const bulkStatus = document.getElementById('bulkStatusSelect').value;
            const bulkCallStatus = document.getElementById('bulkCallStatusSelect').value;

            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
                selectedCountSpan.textContent = `${selectedCount} selected`;
                bulkApplyBtn.disabled = !(bulkStatus || bulkCallStatus);
            } else {
                bulkActions.style.display = 'none';
            }
        }

        document.getElementById('bulkStatusSelect')?.addEventListener('change', function() {
            const selectedCount = getSelectedCheckboxes().length;
            const bulkCallStatus = document.getElementById('bulkCallStatusSelect').value;
            document.getElementById('bulkApplyBtn').disabled = !(selectedCount > 0 && (this.value || bulkCallStatus));
        });

        document.getElementById('bulkCallStatusSelect')?.addEventListener('change', function() {
            const selectedCount = getSelectedCheckboxes().length;
            const bulkStatus = document.getElementById('bulkStatusSelect').value;
            document.getElementById('bulkApplyBtn').disabled = !(selectedCount > 0 && (bulkStatus || this.value));
        });

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAllCheckbox').checked;
            const visibleCheckboxes = getVisibleRowCheckboxes();

            visibleCheckboxes.forEach(cb => cb.checked = selectAll);

            updateBulkActions();
            updateHeaderCheckbox();
        }

        function toggleAllRows() {
            const headerCheckbox = document.getElementById('headerCheckbox').checked;
            const visibleCheckboxes = getVisibleRowCheckboxes();

            visibleCheckboxes.forEach(cb => cb.checked = headerCheckbox);

            updateBulkActions();

            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) selectAllCheckbox.checked = headerCheckbox;

            updateHeaderCheckbox();
        }

        function updateHeaderCheckbox() {
            const headerCheckbox = document.getElementById('headerCheckbox');
            const visibleCheckboxes = getVisibleRowCheckboxes();
            const checkedVisible = visibleCheckboxes.filter(cb => cb.checked);

            if (visibleCheckboxes.length > 0) {
                headerCheckbox.checked = visibleCheckboxes.length === checkedVisible.length;
                headerCheckbox.indeterminate = checkedVisible.length > 0 && checkedVisible.length < visibleCheckboxes.length;
            } else {
                headerCheckbox.checked = false;
                headerCheckbox.indeterminate = false;
            }
        }

        // Bulk update (NOW SAVES TO DB using same update route for each ID)
        async function applyBulkUpdate() {
            const selectedStatus = document.getElementById('bulkStatusSelect').value;
            const selectedCallStatus = document.getElementById('bulkCallStatusSelect').value;

            if (!selectedStatus && !selectedCallStatus) {
                Swal.fire({
                    title: 'No Updates Selected',
                    text: 'Please select at least one status to update',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const selectedCheckboxes = getSelectedCheckboxes();
            if (selectedCheckboxes.length === 0) return;

            let message = `Update <strong>${selectedCheckboxes.length}</strong> orders`;
            if (selectedStatus && selectedCallStatus) {
                message += ` to status <strong>${selectedStatus}</strong> and call status <strong>${selectedCallStatus}</strong>`;
            } else if (selectedStatus) {
                message += ` to status <strong>${selectedStatus}</strong>`;
            } else if (selectedCallStatus) {
                message += ` to call status <strong>${selectedCallStatus}</strong>`;
            }
            message += '?';

            const result = await Swal.fire({
                title: 'Update Multiple Orders?',
                html: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            // Lock UI
            const bulkApplyBtn = document.getElementById('bulkApplyBtn');
            bulkApplyBtn.disabled = true;
            bulkApplyBtn.innerHTML = `<span class="material-icons-round" style="font-size: 18px;">hourglass_top</span> Updating...`;

            const ids = selectedCheckboxes.map(cb => parseInt(cb.value));

            let okCount = 0;
            let failCount = 0;
            const failIds = [];

            // Update in parallel but controlled (small batches)
            const batchSize = 8;
            for (let i = 0; i < ids.length; i += batchSize) {
                const batch = ids.slice(i, i + batchSize);

                const promises = batch.map(async (orderId) => {
                    try {
                        const payload = {};
                        if (selectedStatus) payload.status = selectedStatus;
                        if (selectedCallStatus) payload.call_status = selectedCallStatus;

                        await apiUpdateOrder(orderId, payload);

                        // Update UI locally
                        if (selectedStatus) applyUiUpdate(orderId, 'status', selectedStatus);
                        if (selectedCallStatus) applyUiUpdate(orderId, 'call_status', selectedCallStatus);

                        okCount++;
                    } catch (e) {
                        failCount++;
                        failIds.push(orderId);
                    }
                });

                await Promise.all(promises);
            }

            // Clear selection
            selectedCheckboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
            document.getElementById('headerCheckbox').checked = false;

            document.getElementById('bulkStatusSelect').value = '';
            document.getElementById('bulkCallStatusSelect').value = '';

            updateBulkActions();
            updateHeaderCheckbox();
            filterTable();

            // Restore button
            bulkApplyBtn.innerHTML = `<span class="material-icons-round" style="font-size: 18px;">check</span> Apply to Selected`;
            bulkApplyBtn.disabled = true;

            if (failCount === 0) {
                showToast(`Updated ${okCount} orders successfully`);
            } else {
                Swal.fire({
                    title: 'Bulk Update Completed',
                    html: `<div style="text-align:left">
                            ✅ Success: <strong>${okCount}</strong><br>
                            ❌ Failed: <strong>${failCount}</strong><br>
                            ${failIds.length ? `<small class="text-muted">Failed IDs: ${failIds.join(', ')}</small>` : ''}
                          </div>`,
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('statusToast');
            const toastMessage = document.getElementById('toastMessage');

            toastMessage.textContent = message;
            toast.style.display = 'flex';

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.style.display = 'none';
                    toast.style.opacity = '1';
                }, 300);
            }, 3000);
        }

        // Modal status change handler
        document.getElementById('statusSelect')?.addEventListener('change', function() {
            const status = this.value;
            document.getElementById('deliveryDateField').style.display = 'none';
            document.getElementById('reschedulingDateField').style.display = 'none';
            document.getElementById('returnDateField').style.display = 'none';

            if (status === 'complete') {
                document.getElementById('deliveryDateField').style.display = 'block';
            } else if (status === 'rescheduling') {
                document.getElementById('reschedulingDateField').style.display = 'block';
            } else if (status === 'return') {
                document.getElementById('returnDateField').style.display = 'block';
            }
        });

        function editOrder(id) {
            document.getElementById('updateForm').action = `/seller/order/update/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();

            // not change routes use same route
        }

        function deleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?<br><br>
                       <div class="alert alert-warning mb-0">
                           <small>This will refund your deposit and restore product stock.</small>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/seller/order/destroy/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Initialize
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
