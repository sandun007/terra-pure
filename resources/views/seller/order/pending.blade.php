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

        .page-header { background: linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); color:white; padding:2rem; border-radius:16px; margin-bottom:2rem; box-shadow:0 8px 20px rgba(59,130,246,.2); }
        .page-header h1 { font-size:28px; font-weight:800; margin-bottom:.5rem; }
        .order-count { background:rgba(255,255,255,.2); padding:8px 20px; border-radius:999px; display:inline-block; font-weight:600; }

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

        .badge-platform {
            background:rgba(79,70,229,.1);
            color:var(--primary);
            padding:4px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
            white-space:nowrap;
        }

        .badge-status { padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:4px; }
        .badge-status.pending      { background:#fef3c7; color:#92400e; }
        .badge-status.complete     { background:#d1fae5; color:#065f46; }
        .badge-status.return       { background:#fee2e2; color:#991b1b; }
        .badge-status.rescheduling { background:#fff3cd; color:#856404; }
        .badge-status.cancelled    { background:#f1f5f9; color:#475569; }

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
            .page-header { padding:1.5rem; }
            .page-header h1 { font-size:22px; }
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
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary">
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
                                <th>Call Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
                                <tr data-order-id="{{ $order->id }}"
                                    data-call-status="{{ $order->call_status ?? 'Pending Call' }}"
                                    data-platform="{{ $order->platform ?? 'manual' }}">
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
                                                    {{ \Illuminate\Support\Str::limit($order->product_name, 30) }}
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
                                            {{ ucfirst($order->platform ?? 'Manual') }}
                                        </span>
                                    </td>
                                    <td class="quick-action-cell">
                                        <select class="quick-select call-{{ strtolower(str_replace(' ', '-', $order->call_status ?? 'Pending Call')) }}"
                                                onchange="updateCallStatus({{ $order->id }}, this.value)"
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
                                                    title="Update Order">
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
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary mt-3">
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
                        Update Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
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

    <!-- Quick Status Update Toast -->
    <div id="statusToast" class="status-toast" style="display: none;">
        <span class="material-icons-round" style="color: var(--success);">check_circle</span>
        <span id="toastMessage">Status updated successfully</span>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const UPDATE_URL = (id) => `/seller/order/update/${id}`;

        // Store original orders data
        let orders = [];
        @foreach($orders as $order)
        orders.push({
            id: {{ $order->id }},
            row: document.querySelector(`tr[data-order-id="{{ $order->id }}"]`),
            callStatus: '{{ $order->call_status ?? "Pending Call" }}',
            platform: '{{ $order->platform ?? "manual" }}',
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
        document.getElementById('platformFilter')?.addEventListener('change', filterTable);
        document.getElementById('callStatusFilter')?.addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const platformFilter = document.getElementById('platformFilter')?.value || 'all';
            const callStatusFilter = document.getElementById('callStatusFilter')?.value || 'all';

            let visibleCount = 0;
            let activeFilters = 0;

            if (platformFilter !== 'all') activeFilters++;
            if (callStatusFilter !== 'all') activeFilters++;
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
                const matchesPlatform = platformFilter === 'all' || order.platform === platformFilter;
                const matchesCallStatus = callStatusFilter === 'all' || order.callStatus === callStatusFilter;

                if (matchesSearch && matchesPlatform && matchesCallStatus) {
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
            document.getElementById('callStatusFilter').value = 'all';
            document.getElementById('searchInput').value = '';
            filterTable();
        }

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

        async function updateCallStatus(orderId, newValue) {
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (!row) return;

            const selectEl = row.querySelector('select[onchange*="updateCallStatus"]');
            const oldValue = row.dataset.callStatus || 'Pending Call';

            try {
                selectEl.disabled = true;

                const form = new FormData();
                form.append('_token', CSRF_TOKEN);
                form.append('_method', 'PUT');
                form.append('call_status', newValue);

                const res = await fetch(UPDATE_URL(orderId), {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form
                });

                if (!res.ok) throw new Error('Update failed');

                row.dataset.callStatus = newValue;
                const o = orders.find(x => x.id === orderId);
                if (o) o.callStatus = newValue;

                showToast(`Call status updated to ${newValue}`);
                filterTable();

            } catch (e) {
                selectEl.value = oldValue;
                Swal.fire({ icon: 'error', title: 'Update Failed', text: e.message });
            } finally {
                selectEl.disabled = false;
            }
        }

        function updateBulkActions() {
            const selectedCount = getSelectedCheckboxes().length;
            const bulkActions = document.getElementById('bulkActions');
            const selectedCountSpan = document.getElementById('selectedCount');
            const bulkApplyBtn = document.getElementById('bulkApplyBtn');
            const bulkCallStatus = document.getElementById('bulkCallStatusSelect').value;

            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
                selectedCountSpan.textContent = `${selectedCount} selected`;
                bulkApplyBtn.disabled = !bulkCallStatus;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        document.getElementById('bulkCallStatusSelect')?.addEventListener('change', function() {
            const selectedCount = getSelectedCheckboxes().length;
            document.getElementById('bulkApplyBtn').disabled = !(selectedCount > 0 && this.value);
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

        async function applyBulkUpdate() {
            const selectedCallStatus = document.getElementById('bulkCallStatusSelect').value;

            if (!selectedCallStatus) {
                Swal.fire({ title: 'No Update Selected', text: 'Please select a call status', icon: 'warning' });
                return;
            }

            const selectedCheckboxes = getSelectedCheckboxes();
            if (selectedCheckboxes.length === 0) return;

            const result = await Swal.fire({
                title: 'Update Multiple Orders?',
                html: `Update <strong>${selectedCheckboxes.length}</strong> orders to call status <strong>${selectedCallStatus}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            const bulkApplyBtn = document.getElementById('bulkApplyBtn');
            bulkApplyBtn.disabled = true;
            bulkApplyBtn.innerHTML = `<span class="material-icons-round" style="font-size: 18px;">hourglass_top</span> Updating...`;

            const ids = selectedCheckboxes.map(cb => parseInt(cb.value));
            let okCount = 0, failCount = 0;

            for (const orderId of ids) {
                try {
                    const form = new FormData();
                    form.append('_token', CSRF_TOKEN);
                    form.append('_method', 'PUT');
                    form.append('call_status', selectedCallStatus);

                    const res = await fetch(UPDATE_URL(orderId), {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: form
                    });

                    if (res.ok) {
                        okCount++;
                        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                        if (row) {
                            row.dataset.callStatus = selectedCallStatus;
                            const select = row.querySelector('select[onchange*="updateCallStatus"]');
                            if (select) select.value = selectedCallStatus;
                            const o = orders.find(x => x.id === orderId);
                            if (o) o.callStatus = selectedCallStatus;
                        }
                    } else {
                        failCount++;
                    }
                } catch (e) {
                    failCount++;
                }
            }

            selectedCheckboxes.forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
            document.getElementById('headerCheckbox').checked = false;
            document.getElementById('bulkCallStatusSelect').value = '';
            updateBulkActions();
            filterTable();

            bulkApplyBtn.innerHTML = `<span class="material-icons-round" style="font-size: 18px;">check</span> Apply to Selected`;
            bulkApplyBtn.disabled = true;

            if (failCount === 0) {
                showToast(`Updated ${okCount} orders successfully`);
            } else {
                Swal.fire({ title: 'Bulk Update Completed', html: `✅ Success: ${okCount}<br>❌ Failed: ${failCount}`, icon: 'warning' });
            }
        }

        function showToast(message) {
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

        function editOrder(id) {
            document.getElementById('updateForm').action = `/seller/order/update/${id}`;
            new bootstrap.Modal(document.getElementById('updateModal')).show();
        }

        function deleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/seller/order/destroy/${id}`;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = CSRF_TOKEN;
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
