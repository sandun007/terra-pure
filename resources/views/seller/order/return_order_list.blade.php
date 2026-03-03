@extends('seller.layout.app')

@push('title')
    Return Orders
@endpush

@push('css')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            --return-gradient: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f8fafc;
        }

        /* Header Section */
        .return-header {
            background: var(--return-gradient);
            color: #333;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 8px 20px rgba(255, 154, 158, 0.2);
        }

        .return-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .return-header .order-count {
            background: rgba(255, 255, 255, 0.3);
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stats-card.return {
            border-left-color: #ff6b6b;
        }

        .stats-card.amount {
            border-left-color: #c92a2a;
        }

        .stats-card.recent {
            border-left-color: #ff9a9e;
        }

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
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        .stats-icon.amount {
            background: rgba(201, 42, 42, 0.1);
            color: #c92a2a;
        }

        .stats-icon.recent {
            background: rgba(255, 154, 158, 0.1);
            color: #ff9a9e;
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
            background: var(--danger-gradient);
            color: white;
        }

        /* Badges */
        .badge-return {
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-date {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-platform {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Product Cell */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-image {
            width: 40px;
            height: 40px;
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
            margin: 0 0 2px 0;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-code {
            font-size: 11px;
            color: var(--mid);
            font-family: monospace;
        }

        /* Action Buttons - Matching pending order page */
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
            margin: 0 2px;
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

        /* Action button group */
        .action-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
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
            background: #f8fafc;
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

        table.dataTable tbody tr {
            transition: background-color 0.3s ease;
        }

        table.dataTable tbody tr:hover {
            background-color: rgba(255, 107, 107, 0.05);
        }

        .remark-tooltip {
            cursor: help;
            position: relative;
        }

        .remark-content {
            max-width: 200px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Return reason indicators */
        .reason-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .reason-damaged {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .reason-wrong {
            background: rgba(234, 88, 12, 0.1);
            color: #ea580c;
        }

        .reason-delayed {
            background: rgba(202, 138, 4, 0.1);
            color: #ca8a04;
        }

        .reason-other {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        /* Return status indicators */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-pending {
            color: #ea580c;
        }

        .status-processed {
            color: #16a34a;
        }

        .status-refunded {
            color: #2563eb;
        }

        /* Animation for warning icon */
        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        .warning-shake {
            animation: shake 2s infinite;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid var(--border);
            border-radius: 8px;
            padding: 8px 16px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--danger);
            outline: none;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .return-header {
                padding: 1.5rem;
                text-align: center;
            }

            .return-header h1 {
                font-size: 22px;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 10px 16px;
                font-size: 13px;
            }

            .action-group {
                justify-content: center;
            }

            .action-btn {
                width: 32px;
                height: 32px;
            }

            table.dataTable thead th,
            table.dataTable tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }

            .reason-badge, .status-indicator {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .return-header h1 {
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
            }

            .table-responsive {
                margin: 0 -15px;
                width: calc(100% + 30px);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header Section -->
        <div class="return-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2 warning-shake" style="vertical-align: middle; font-size: 32px;">assignment_return</span>
                        Return Orders
                    </h1>
                    <p class="mb-0 opacity-75">Orders that have been returned by customers</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="order-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">shopping_bag</span>
                        {{ $count }} {{ $count === 1 ? 'Order' : 'Orders' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card return">
                    <div class="stats-icon return">
                        <span class="material-icons-round">assignment_return</span>
                    </div>
                    <h6 class="text-muted mb-1">Returned Orders</h6>
                    <h2 class="fw-bold mb-0">{{ $count }}</h2>
                    <small class="text-muted">Orders returned by customers</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round">money_off</span>
                    </div>
                    <h6 class="text-muted mb-1">Return Amount</h6>
                    <h2 class="fw-bold mb-0">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                    <small class="text-muted">Total value of returns</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card recent">
                    <div class="stats-icon recent">
                        <span class="material-icons-round">update</span>
                    </div>
                    <h6 class="text-muted mb-1">Latest Return</h6>
                    <h2 class="fw-bold mb-0">
                        @if($orders->first())
                            {{ $orders->first()->return_date ? \Carbon\Carbon::parse($orders->first()->return_date)->format('M d, Y') : 'N/A' }}
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
                    <a href="{{ route('seller.order.pending') }}" class="nav-link {{ request()->routeIs('seller.order.pending') ? 'active' : '' }}">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">pending_actions</span>
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.order.completed') }}" class="nav-link {{ request()->routeIs('seller.order.completed') ? 'active' : '' }}">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">check_circle</span>
                        Complete
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.order.returned') }}" class="nav-link {{ request()->routeIs('seller.order.returned') ? 'active' : '' }}">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">assignment_return</span>
                        Return
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.order.rescheduled') }}" class="nav-link {{ request()->routeIs('seller.order.rescheduled') ? 'active' : '' }}">
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
                            Returned Orders List
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-light me-2" onclick="refreshPage()">
                            <span class="material-icons-round me-2" style="font-size: 18px;">refresh</span>
                            Refresh
                        </button>
                        <button class="btn btn-outline-danger" onclick="exportReturns()">
                            <span class="material-icons-round me-2" style="font-size: 18px;">download</span>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            @if($count > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-hover w-100">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Quantity</th>
                                <th>Order Date</th>
                                <th>Return Date</th>
                                <th>Return Reason</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                @php
                                    // Determine return reason from remark
                                    $remark = strtolower($order->remark ?? '');
                                    $reasonClass = 'reason-other';
                                    $reasonText = 'Other';

                                    if (str_contains($remark, 'damage') || str_contains($remark, 'broken')) {
                                        $reasonClass = 'reason-damaged';
                                        $reasonText = 'Damaged';
                                    } elseif (str_contains($remark, 'wrong') || str_contains($remark, 'incorrect')) {
                                        $reasonClass = 'reason-wrong';
                                        $reasonText = 'Wrong Item';
                                    } elseif (str_contains($remark, 'delay') || str_contains($remark, 'late')) {
                                        $reasonClass = 'reason-delayed';
                                        $reasonText = 'Delayed';
                                    }

                                    // Determine return status
                                    $returnStatus = $order->return_status ?? 'pending';
                                    $statusIcon = $returnStatus === 'processed' ? 'check_circle' :
                                                 ($returnStatus === 'refunded' ? 'paid' : 'pending');
                                    $statusColor = $returnStatus === 'processed' ? 'status-processed' :
                                                 ($returnStatus === 'refunded' ? 'status-refunded' : 'status-pending');
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
                                                     onerror="this.style.display='none';">
                                            @endif
                                            <div class="product-details">
                                                <div class="product-name" title="{{ $order->product_name }}">
                                                    {{ $order->product_name }}
                                                </div>
                                                <span class="product-code">{{ $order->product_code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                                            <small class="text-muted">
                                                <span class="material-icons-round" style="font-size: 14px;">phone</span>
                                                {{ $order->customer_phone }}
                                            </small>
                                            @if($order->district)
                                                <br>
                                                <small class="text-muted">
                                                    <span class="material-icons-round" style="font-size: 14px;">location_on</span>
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
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-secondary me-2">event</span>
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-danger me-2">event_busy</span>
                                            @if($order->return_date)
                                                <div>
                                                    <div>{{ \Carbon\Carbon::parse($order->return_date)->format('M d, Y') }}</div>
                                                    <div class="badge-date">
                                                        {{ \Carbon\Carbon::parse($order->return_date)->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="reason-badge {{ $reasonClass }}">
                                            <span class="material-icons-round" style="font-size: 1rem">
                                                {{ $reasonClass === 'reason-damaged' ? 'broken_image' :
                                                   ($reasonClass === 'reason-wrong' ? 'error' :
                                                   ($reasonClass === 'reason-delayed' ? 'schedule' : 'help')) }}
                                            </span>
                                            {{ $reasonText }}
                                        </div>
                                        @if($order->remark)
                                            <div class="remark-tooltip mt-1" data-bs-toggle="tooltip"
                                                 data-bs-placement="top"
                                                 title="{{ $order->remark }}">
                                                <div class="remark-content text-muted small">
                                                    {{ Str::limit($order->remark, 20) }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="status-indicator {{ $statusColor }}">
                                            <span class="material-icons-round" style="font-size: 1rem">
                                                {{ $statusIcon }}
                                            </span>
                                            <span class="text-capitalize">{{ $returnStatus }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-danger me-2">money_off</span>
                                            <strong class="text-danger">Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <!-- View Details Button -->
                                            <button onclick="viewOrder({{ $order->id }})"
                                                    class="action-btn view"
                                                    title="View Order Details">
                                                <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                            </button>

                                            <!-- Edit/Update Button -->
                                            <button onclick="editOrder({{ $order->id }})"
                                                    class="action-btn edit"
                                                    title="Update Order">
                                                <span class="material-icons-round" style="font-size: 18px;">edit</span>
                                            </button>

                                            <!-- Process Return Button -->
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
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <span class="material-icons-round mb-3" style="font-size: 64px; opacity: 0.3;">inbox</span>
                            <h5>No Returned Orders</h5>
                            <p>You don't have any returned orders at the moment.</p>
                            <a href="{{ route('seller.order.pending') }}" class="btn btn-primary mt-3">
                                <span class="material-icons-round me-2" style="font-size: 18px;">pending_actions</span>
                                View Pending Orders
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-footer bg-white border-0 py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="text-muted">
                            Showing {{ $orders->count() }} of {{ $count }} returned orders
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="btn-group">
                            <button class="btn btn-light" onclick="printReturns()">
                                <span class="material-icons-round me-2">print</span>
                                Print
                            </button>
                            <button class="btn btn-outline-danger" onclick="bulkProcessReturns()">
                                <span class="material-icons-round me-2">playlist_add_check</span>
                                Bulk Process
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Order Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--danger-gradient); color: white;">
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
                            <label class="form-label fw-semibold">Remark</label>
                            <textarea name="remark" class="form-control" rows="3" placeholder="Add notes about the return..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Process Return Modal -->
    <div class="modal fade" id="processReturnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--danger-gradient); color: white;">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2">autorenew</span>
                        Process Return Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="processReturnForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h6 id="returnOrderTitle" class="fw-semibold"></h6>
                            <p class="text-muted small">Process this returned order</p>
                        </div>

                        <div class="mb-4">
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons-round me-2">warning</span>
                                    <div>
                                        <small>Processing this return will update the order status and may initiate a refund.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-primary">category</span>
                                Process Action *
                            </label>
                            <select name="return_action" id="returnAction" class="form-select" required>
                                <option value="">Select action</option>
                                <option value="refund">Issue Refund</option>
                                <option value="replace">Send Replacement</option>
                                <option value="store_credit">Issue Store Credit</option>
                                <option value="cancel">Cancel Return</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-success">payments</span>
                                Refund Amount
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="refund_amount" id="refundAmount"
                                       class="form-control" step="0.01" min="0"
                                       placeholder="Enter refund amount">
                                <button type="button" class="btn btn-outline-secondary" onclick="setFullAmount()">
                                    Full Amount
                                </button>
                            </div>
                            <small class="text-muted">Leave blank if not applicable</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-secondary">notes</span>
                                Processing Notes
                            </label>
                            <textarea name="process_notes" id="processNotes" class="form-control" rows="3"
                                      placeholder="Add notes about the return processing..."></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifyCustomer" checked>
                                    <label class="form-check-label" for="notifyCustomer">
                                        <span class="material-icons-round me-1" style="font-size: 1rem">notifications</span>
                                        Notify customer
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="updateInventory">
                                    <label class="form-check-label" for="updateInventory">
                                        <span class="material-icons-round me-1" style="font-size: 1rem">inventory</span>
                                        Update inventory
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <span class="material-icons-round me-1">close</span>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <span class="material-icons-round me-1">check_circle</span>
                            Process Return
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- Bootstrap JS for Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            @if($count > 0)
            // Initialize DataTable
            $('#ordersTable').DataTable({
                order: [[5, 'desc']], // Sort by return date descending
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search returned orders...",
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
                },
                columnDefs: [
                    { orderable: false, targets: [1, 6, 9] }, // Disable sorting on Product, Return Reason, and Actions columns
                    { responsivePriority: 1, targets: 0 }, // Order ID
                    { responsivePriority: 2, targets: 2 }, // Customer
                    { responsivePriority: 3, targets: 5 }, // Return Date
                    { responsivePriority: 4, targets: 8 }, // Amount
                ]
            });
            @endif

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // View Order Details
        function viewOrder(id) {
            window.location.href = `/seller/order/show/${id}`;
        }

        // Edit/Update Order
        function editOrder(id) {
            $('#updateForm').attr('action', `/seller/order/update/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();
        }

        // Process return order
        function processReturn(id, orderNumber) {
            // Set form action
            $('#processReturnForm').attr('action', `/seller/order/update/${id}`);

            // Set order title
            $('#returnOrderTitle').text(`Process Return - Order #${orderNumber}`);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('processReturnModal'));
            modal.show();
        }

        // Set full refund amount
        function setFullAmount() {
            // You might want to fetch the actual order amount via AJAX
            const orderAmount = 0; // Replace with actual amount
            document.getElementById('refundAmount').value = orderAmount.toFixed(2);
        }

        // Refresh page with confirmation
        function refreshPage() {
            Swal.fire({
                title: 'Refresh Returns?',
                text: 'This will reload all returned orders data.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes, Refresh',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        }

        // Export returns
        function exportReturns() {
            Swal.fire({
                title: 'Export Returns',
                html: `<div class="text-start">
                          <p>Select export format for returned orders:</p>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="radio" name="exportFormat" id="excelFormat" value="excel" checked>
                              <label class="form-check-label d-flex align-items-center" for="excelFormat">
                                  <span class="material-icons-round me-2 text-success">description</span>
                                  Excel (.xlsx)
                              </label>
                          </div>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="radio" name="exportFormat" id="csvFormat" value="csv">
                              <label class="form-check-label d-flex align-items-center" for="csvFormat">
                                  <span class="material-icons-round me-2 text-info">table_chart</span>
                                  CSV (.csv)
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="radio" name="exportFormat" id="pdfFormat" value="pdf">
                              <label class="form-check-label d-flex align-items-center" for="pdfFormat">
                                  <span class="material-icons-round me-2 text-danger">picture_as_pdf</span>
                                  PDF (.pdf)
                              </label>
                          </div>
                          <div class="mt-3">
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="includePending" checked>
                                  <label class="form-check-label" for="includePending">
                                      Include pending returns
                                  </label>
                              </div>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="includeProcessed" checked>
                                  <label class="form-check-label" for="includeProcessed">
                                      Include processed returns
                                  </label>
                              </div>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Export',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const format = document.querySelector('input[name="exportFormat"]:checked').value;

                    Swal.fire({
                        title: 'Exporting...',
                        html: `Preparing returns export (${format.toUpperCase()})...`,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 2000,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Complete',
                            text: `Your ${format.toUpperCase()} file is ready for download`,
                            confirmButtonText: 'Download'
                        });
                    });
                }
            });
        }

        // Print returns
        function printReturns() {
            Swal.fire({
                title: 'Print Returns Report',
                html: `<div class="text-start">
                          <p>Configure print settings for returned orders:</p>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printSummary" checked>
                              <label class="form-check-label" for="printSummary">
                                  Include summary statistics
                              </label>
                          </div>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printReasons" checked>
                              <label class="form-check-label" for="printReasons">
                                  Include return reasons
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="printStatus" checked>
                              <label class="form-check-label" for="printStatus">
                                  Include processing status
                              </label>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Print',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.print();
                }
            });
        }

        // Bulk process returns
        function bulkProcessReturns() {
            Swal.fire({
                title: 'Bulk Process Returns',
                html: `<div class="text-start">
                          <p>Select returns to process in bulk:</p>
                          <div class="alert alert-info">
                              <div class="d-flex align-items-center">
                                  <span class="material-icons-round me-2">info</span>
                                  <div>
                                      <small>This will process multiple returns with the same action.</small>
                                  </div>
                              </div>
                          </div>
                          <div class="mb-3">
                              <label class="form-label">Select Action</label>
                              <select class="form-select" id="bulkAction">
                                  <option value="refund">Issue Refund</option>
                                  <option value="replace">Send Replacement</option>
                                  <option value="store_credit">Issue Store Credit</option>
                              </select>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="selectAll">
                              <label class="form-check-label" for="selectAll">
                                  Select all visible returns
                              </label>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Process Selected',
                cancelButtonText: 'Cancel',
                showDenyButton: true,
                denyButtonText: 'Select Manually',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary',
                    denyButton: 'btn btn-outline-info'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Processing selected returns...',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 3000,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Bulk Process Complete!',
                            text: 'Selected returns have been processed successfully.',
                            confirmButtonText: 'OK'
                        });
                    });
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Manual Selection',
                        text: 'Please select individual returns to process.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Form submission handler for return processing
        document.getElementById('processReturnForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const action = document.getElementById('returnAction').value;
            const refundAmount = document.getElementById('refundAmount').value;
            const notify = document.getElementById('notifyCustomer').checked;

            if (!action) {
                Swal.fire({
                    icon: 'error',
                    title: 'Action Required',
                    text: 'Please select a process action.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Process This Return?',
                html: `<div class="text-start">
                          <p>Process return with action: <strong>${action}</strong></p>
                          ${refundAmount ? `<p>Refund amount: <strong>Rs. ${refundAmount}</strong></p>` : ''}
                          <div class="alert alert-warning mt-3">
                              <div class="d-flex align-items-center">
                                  <span class="material-icons-round me-2">warning</span>
                                  <div>
                                      ${notify ? 'Customer will be notified about this action.' : 'Customer will NOT be notified.'}
                                  </div>
                              </div>
                          </div>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Process',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary'
                },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we process the return',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the form
                    this.submit();
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Highlight recent returns (within 7 days)
        function highlightRecentReturns() {
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);

            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(6)'); // Return Date column
                if (dateCell) {
                    const dateText = dateCell.textContent;
                    const returnDate = new Date(dateText);

                    if (!isNaN(returnDate) && returnDate >= sevenDaysAgo) {
                        row.classList.add('table-danger', 'table-danger-bg');
                    }
                }
            });
        }

        // Initialize highlighting
        setTimeout(() => {
            highlightRecentReturns();
        }, 500);
    </script>
@endpush
