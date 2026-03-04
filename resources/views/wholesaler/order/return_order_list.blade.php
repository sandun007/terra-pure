@extends('wholesaler.layouts.app')

@push('title')
    Return Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
        }

        /* Header Section */
        .return-header {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
        }

        .return-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .return-header .order-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .stats-card.return { border-left-color: var(--danger); }
        .stats-card.amount { border-left-color: var(--primary); }
        .stats-card.recent { border-left-color: var(--info); }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 28px;
        }

        .stats-icon.return {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stats-icon.amount {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .stats-icon.recent {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        /* Navigation Tabs */
        .nav-tabs {
            border: none;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
            background: var(--danger);
            color: white;
        }

        /* Product Info Display */
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
            background: var(--bg);
        }

        .product-image-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: var(--bg);
            border: 2px dashed var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--mid);
        }

        .product-details {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-weight: 600;
            color: var(--dark);
            margin: 0 0 4px 0;
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
            display: inline-block;
        }

        /* Table Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
        }

        .table-responsive {
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            background: var(--bg);
            border-bottom: 2px solid var(--border);
            padding: 1rem;
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.dataTable tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        table.dataTable tbody tr:hover {
            background-color: rgba(239, 68, 68, 0.03);
        }

        /* Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-return {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-platform {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-call-status {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-reason {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-reason.damaged {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-reason.wrong-item {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-reason.customer-request {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-reason.other {
            background: #e5e7eb;
            color: #374151;
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
            transition: all 0.2s;
            cursor: pointer;
        }

        .action-btn.view {
            background: var(--primary);
            color: white;
        }

        .action-btn.edit {
            background: var(--warning);
            color: white;
        }

        .action-btn.process {
            background: var(--danger);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .modal-header {
            background: var(--danger);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border-bottom: none;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid var(--border);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--danger);
            border: none;
        }

        .btn-primary:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        /* Search Box */
        .search-box {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .search-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mid);
            font-size: 20px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 45px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: var(--danger);
            outline: none;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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

        /* Tooltip */
        .remark-tooltip {
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
        }

        .text-small {
            font-size: 11px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">

        <!-- Header -->
        <div class="return-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="vertical-align: middle; font-size: 32px;">assignment_return</span>
                        Return Orders
                    </h1>
                    <p class="mb-0 opacity-75">Manage and track returned orders</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="order-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">shopping_bag</span>
                        {{ $count }} {{ $count === 1 ? 'Order' : 'Orders' }}
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
            <div class="col-md-4">
                <div class="stats-card return">
                    <div class="stats-icon return">
                        <span class="material-icons-round">assignment_return</span>
                    </div>
                    <h6 class="text-muted mb-1">Total Returns</h6>
                    <h2 class="fw-bold mb-0">{{ $count }}</h2>
                    <small class="text-muted">Returned orders</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round">payments</span>
                    </div>
                    <h6 class="text-muted mb-1">Return Amount</h6>
                    <h2 class="fw-bold mb-0">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                    <small class="text-muted">Total value of returns</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card recent">
                    <div class="stats-icon recent">
                        <span class="material-icons-round">schedule</span>
                    </div>
                    <h6 class="text-muted mb-1">Latest Return</h6>
                    <h2 class="fw-bold mb-0">
                        @if($orders->first() && $orders->first()->return_date)
                            {{ \Carbon\Carbon::parse($orders->first()->return_date)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </h2>
                    <small class="text-muted">Most recent return</small>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('wholesaler.order.pending-order') }}" class="nav-link">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">pending_actions</span>
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wholesaler.order.complete-order') }}" class="nav-link">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">check_circle</span>
                        Complete
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wholesaler.order.return-order') }}" class="nav-link active">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">assignment_return</span>
                        Return
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wholesaler.order.rescheduling-order') }}" class="nav-link">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">event_repeat</span>
                        Rescheduling
                    </a>
                </li>
            </ul>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">
                            <span class="material-icons-round me-2" style="vertical-align: middle;">list_alt</span>
                            Return Orders List
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('wholesaler.order.new') }}" class="btn btn-primary">
                            <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">add</span>
                            New Order
                        </a>
                    </div>
                </div>
            </div>

            @if($count > 0)
                <!-- Search Box -->
                <div class="search-box">
                    <div class="search-wrapper">
                        <span class="material-icons-round search-icon">search</span>
                        <input type="text" id="searchInput" class="search-input" placeholder="Search by order number, customer, phone, product...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="ordersTable">
                            <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Order Date</th>
                                <th>Return Date</th>
                                <th>Return Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
                                @php
                                    // Determine return reason from remark
                                    $remark = strtolower($order->remark ?? '');
                                    $reasonClass = 'other';
                                    $reasonText = 'Other';

                                    if (str_contains($remark, 'damage') || str_contains($remark, 'broken') || str_contains($remark, 'defective')) {
                                        $reasonClass = 'damaged';
                                        $reasonText = 'Damaged';
                                    } elseif (str_contains($remark, 'wrong') || str_contains($remark, 'incorrect') || str_contains($remark, 'not what')) {
                                        $reasonClass = 'wrong-item';
                                        $reasonText = 'Wrong Item';
                                    } elseif (str_contains($remark, 'change') || str_contains($remark, 'no longer') || str_contains($remark, 'cancel')) {
                                        $reasonClass = 'customer-request';
                                        $reasonText = 'Customer Request';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-danger me-2">assignment_return</span>
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
                                        @if($order->return_date)
                                            <div>
                                                <div>{{ \Carbon\Carbon::parse($order->return_date)->format('M d, Y') }}</div>
                                                <small class="text-muted text-small">{{ \Carbon\Carbon::parse($order->return_date)->diffForHumans() }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-reason {{ $reasonClass }}">
                                            {{ $reasonText }}
                                        </span>
                                        @if($order->remark)
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                                  data-bs-placement="top"
                                                  title="{{ $order->remark }}">
                                                <span class="material-icons-round text-muted" style="font-size: 14px; cursor: help;">info</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-status badge-return">
                                            <span class="material-icons-round me-1" style="font-size: 12px; vertical-align: middle;">assignment_return</span>
                                            Returned
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('wholesaler.order.show', $order->id) }}"
                                               class="action-btn view"
                                               title="View Details">
                                                <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }})"
                                                    class="action-btn edit"
                                                    title="Update Status">
                                                <span class="material-icons-round" style="font-size: 18px;">edit</span>
                                            </button>
                                            <button onclick="processReturn({{ $order->id }}, '{{ $order->order_number }}')"
                                                    class="action-btn process"
                                                    title="Process Return">
                                                <span class="material-icons-round" style="font-size: 18px;">autorenew</span>
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
                        <h5>No Return Orders</h5>
                        <p class="text-muted">You don't have any returned orders at the moment.</p>
                        <a href="{{ route('wholesaler.order.pending-order') }}" class="btn btn-primary mt-3">
                            <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">pending_actions</span>
                            View Pending Orders
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
                        Update Return Order
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
                                <option value="return" selected>Return</option>
                                <option value="rescheduling">Rescheduling</option>
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
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Return Date</label>
                            <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark / Return Reason</label>
                            <textarea name="remark" class="form-control" rows="3" placeholder="Add notes about the return..."></textarea>
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

    <!-- Process Return Modal -->
    <div class="modal fade" id="processReturnModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--danger); color: white;">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">autorenew</span>
                        Process Return
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="processReturnForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <h6 id="returnOrderTitle" class="fw-bold"></h6>
                        </div>

                        <div class="alert alert-warning mb-3">
                            <div class="d-flex align-items-center">
                                <span class="material-icons-round me-2">warning</span>
                                <small>Processing this return will update the order status.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Process Action</label>
                            <select name="process_action" class="form-select" required>
                                <option value="">Select Action</option>
                                <option value="refund">Issue Refund</option>
                                <option value="replace">Send Replacement</option>
                                <option value="store_credit">Issue Store Credit</option>
                                <option value="restock">Restock Only</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Refund Amount (if applicable)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="refund_amount" id="refundAmount"
                                       class="form-control" step="0.01" min="0"
                                       placeholder="Enter amount">
                                <button type="button" class="btn btn-outline-secondary" onclick="setFullAmount()">Full</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Process Notes</label>
                            <textarea name="process_notes" class="form-control" rows="2"
                                      placeholder="Add processing notes..."></textarea>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="notifyCustomer" checked>
                            <label class="form-check-label" for="notifyCustomer">
                                Notify customer about this action
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="restockInventory" checked>
                            <label class="form-check-label" for="restockInventory">
                                Restock returned items
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Process Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            @if($count > 0)
            // Initialize DataTable
            var table = $('#ordersTable').DataTable({
                order: [[6, 'desc']], // Sort by return date column
                pageLength: 25,
                responsive: true,
                autoWidth: false,
                searching: true,
                lengthChange: true,
                columnDefs: [
                    { orderable: false, targets: [1, 7, 9] }, // Disable sorting on Product, Return Reason, and Actions columns
                    { responsivePriority: 1, targets: 0 }, // Order Number
                    { responsivePriority: 2, targets: 2 }, // Customer
                    { responsivePriority: 3, targets: 4 }, // Amount
                    { responsivePriority: 4, targets: 6 }, // Return Date
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search return orders...",
                    lengthMenu: "Show _MENU_ orders",
                    info: "Showing _START_ to _END_ of _TOTAL_ orders",
                    infoEmpty: "No orders available",
                    zeroRecords: "No matching orders found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Custom search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            @endif

            // Status change handler
            $('#statusSelect').on('change', function() {
                const status = $(this).val();
                // You can add logic here if needed
            });

            // Auto-hide alerts
            setTimeout(() => {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        });

        function viewOrder(id) {
            window.location.href = `/wholesaler/order/show/${id}`;
        }

        function editOrder(id) {
            $('#updateForm').attr('action', `/wholesaler/order/update/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();
        }

        function processReturn(id, orderNumber) {
            $('#processReturnForm').attr('action', `/wholesaler/order/update/${id}`);
            $('#returnOrderTitle').text(`Order #${orderNumber}`);

            // Set default refund amount to order total (you might want to fetch this via AJAX)
            @if($orders->first())
            $('#refundAmount').val('{{ $orders->first()->total_amount }}');
            @endif

            const modal = new bootstrap.Modal(document.getElementById('processReturnModal'));
            modal.show();
        }

        function setFullAmount() {
            @if($orders->first())
            $('#refundAmount').val('{{ $orders->first()->total_amount }}');
            @endif
        }

        function deleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?<br><br>
                       <div class="alert alert-warning mb-0">
                           <small>This action cannot be undone.</small>
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
                    form.action = `/wholesaler/order/destroy/${id}`;

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
    </script>
@endpush
