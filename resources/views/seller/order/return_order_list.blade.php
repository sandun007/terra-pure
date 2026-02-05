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
        }

        .return-header {
            background: var(--return-gradient);
            color: #333;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
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

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            font-weight: 600;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(255, 107, 107, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: var(--danger-gradient);
            color: white;
            border: none;
        }

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

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .action-btn.view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .action-btn.process {
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .dataTables_wrapper {
            padding: 0;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        table.dataTable {
            margin: 0 !important;
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
        }

        table.dataTable tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .return-header {
                padding: 1.5rem;
                text-align: center;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
                margin-bottom: 5px;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
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
                <div class="col-md-6">
                    <h1 class="h2 mb-3 fw-bold">
                        <span class="material-icons-round me-2 warning-shake">assignment_return</span>
                        Return Orders
                    </h1>
                    <p class="mb-0 opacity-75">Orders that have been returned by customers</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-50 rounded-pill px-4 py-2">
                        <span class="fw-bold">{{ $count }}</span> Orders Returned
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card return">
                    <div class="stats-icon return">
                        <span class="material-icons-round" style="font-size: 2rem">assignment_return</span>
                    </div>
                    <h4 class="fw-bold mb-2">Returned Orders</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-red me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">{{ $count }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round" style="font-size: 2rem">money_off</span>
                    </div>
                    <h4 class="fw-bold mb-2">Return Amount</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-dark-red me-2">trending_down</span>
                        <h3 class="mb-0 fw-bold">
                            Rs. {{ number_format($orders->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card recent">
                    <div class="stats-icon recent">
                        <span class="material-icons-round" style="font-size: 2rem">update</span>
                    </div>
                    <h4 class="fw-bold mb-2">Recent Returns</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-pink me-2">schedule</span>
                        <h3 class="mb-0 fw-bold">
                            @if($orders->first())
                                {{ $orders->first()->return_date ? $orders->first()->return_date->format('M d') : 'N/A' }}
                            @else
                                N/A
                            @endif
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <ul class="nav nav-tabs nav-tabs-bordered" id="orderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.pending-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">pending_actions</span>
                                    Pending Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.complete-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">check_circle</span>
                                    Complete Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.return-order') }}"
                                   class="nav-link active d-flex align-items-center">
                                    <span class="material-icons-round me-2">assignment_return</span>
                                    Return Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.rescheduling-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">schedule</span>
                                    Rescheduling
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-header bg-white border-0 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="h5 mb-0 fw-bold">
                                    <span class="material-icons-round me-2">list_alt</span>
                                    Returned Orders List
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn btn-light me-2" onclick="refreshPage()">
                                    <span class="material-icons-round me-2">refresh</span>
                                    Refresh
                                </button>
                                <button class="btn btn-outline-danger" onclick="exportReturns()">
                                    <span class="material-icons-round me-2">download</span>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="order-table" class="table table-hover w-100">
                                <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Order Date</th>
                                    <th>Return Date</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>District</th>
                                    <th>Return Reason</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
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

                                        // Determine return status (you might have this field in your model)
                                        $returnStatus = $order->return_status ?? 'pending';
                                        $statusIcon = $returnStatus === 'processed' ? 'check_circle' :
                                                     ($returnStatus === 'refunded' ? 'paid' : 'pending');
                                        $statusColor = $returnStatus === 'processed' ? 'status-processed' :
                                                     ($returnStatus === 'refunded' ? 'status-refunded' : 'status-pending');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-danger me-2">
                                                        assignment_return
                                                    </span>
                                                <strong>{{ $order->order_id }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-secondary me-2">
                                                        calendar_month
                                                    </span>
                                                {{ $order->order_date->format('Y.m.d') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-danger me-2">
                                                        event_busy
                                                    </span>
                                                @if($order->return_date)
                                                    <div>
                                                        <div>{{ $order->return_date->format('Y.m.d') }}</div>
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
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-success me-2">
                                                        person
                                                    </span>
                                                <span class="text-truncate" style="max-width: 120px">
                                                        {{ $order->customer_name }}
                                                    </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-secondary me-2">
                                                        phone
                                                    </span>
                                                {{ $order->customer_phone ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $order->district ?? 'N/A' }}
                                                </span>
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
                                                    <span class="material-icons-round text-danger me-2">
                                                        money_off
                                                    </span>
                                                <strong class="text-danger">
                                                    Rs. {{ number_format($order->total_amount, 2) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <button class="action-btn view"
                                                        onclick="viewOrder({{ $order->id }})">
                                                    <span class="material-icons-round">visibility</span>
                                                    View
                                                </button>
                                                <button class="action-btn process"
                                                        onclick="processReturn({{ $order->id }}, '{{ $order->order_id }}')">
                                                    <span class="material-icons-round">autorenew</span>
                                                    Process
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    <script>
        // Initialize DataTable
        $(document).ready(function () {
            const table = $('#order-table').DataTable({
                order: [[2, 'desc']], // Sort by return date descending
                pageLength: 10,
                responsive: true,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: '<span class="material-icons-round">search</span>',
                    searchPlaceholder: 'Search returns...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ returns',
                    infoEmpty: 'No returned orders available',
                    zeroRecords: 'No matching returns found',
                    paginate: {
                        first: '<span class="material-icons-round">first_page</span>',
                        last: '<span class="material-icons-round">last_page</span>',
                        next: '<span class="material-icons-round">chevron_right</span>',
                        previous: '<span class="material-icons-round">chevron_left</span>'
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 2 }, // Return Date
                    { responsivePriority: 2, targets: -1 }, // Actions
                    { responsivePriority: 3, targets: 3 }, // Customer
                    { responsivePriority: 4, targets: 7 }, // Status
                    { orderable: false, targets: [6, -1] } // Reason and Actions
                ]
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

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

        // View order details
        function viewOrder(orderId) {
            window.location.href = `/seller/order/${orderId}`;
        }

        // Process return order
        function processReturn(orderId, orderNumber) {
            // Set form action
            $('#processReturnForm').attr('action', `/seller/order/${orderId}/process-return`);

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
                    // Implement print logic here
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
                    // Show manual selection interface
                    Swal.fire({
                        title: 'Manual Selection',
                        text: 'Manual selection feature would open here.',
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
            const orderTitle = document.getElementById('returnOrderTitle').textContent;

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
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Highlight recent returns (within 7 days)
        function highlightRecentReturns() {
            const rows = document.querySelectorAll('#order-table tbody tr');
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);

            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(3)');
                if (dateCell) {
                    const dateText = dateCell.textContent;
                    const returnDate = new Date(dateText.replace(/\./g, '-'));

                    if (returnDate >= sevenDaysAgo) {
                        row.classList.add('table-danger', 'table-danger-bg');
                    }
                }
            });
        }

        // Initialize highlighting
        highlightRecentReturns();
    </script>
@endpush
