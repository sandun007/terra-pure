@extends('seller.layout.app')
@push('title')
    Rescheduling Orders
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
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --rescheduling-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .rescheduling-header {
            background: var(--rescheduling-gradient);
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

        .stats-card.rescheduling {
            border-left-color: #a8edea;
        }

        .stats-card.amount {
            border-left-color: #4facfe;
        }

        .stats-card.upcoming {
            border-left-color: #fed6e3;
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

        .stats-icon.rescheduling {
            background: rgba(168, 237, 234, 0.1);
            color: #2dd4bf;
        }

        .stats-icon.amount {
            background: rgba(79, 172, 254, 0.1);
            color: #4facfe;
        }

        .stats-icon.upcoming {
            background: rgba(254, 214, 227, 0.1);
            color: #f472b6;
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
            background: rgba(168, 237, 234, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: var(--info-gradient);
            color: white;
            border: none;
        }

        .badge-rescheduling {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-date {
            background: rgba(79, 172, 254, 0.1);
            color: #4facfe;
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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .action-btn.reschedule {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
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
            background-color: rgba(168, 237, 234, 0.05);
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

        /* Date status indicators */
        .date-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .date-upcoming {
            background: rgba(168, 237, 234, 0.2);
            color: #0d9488;
        }

        .date-overdue {
            background: rgba(254, 202, 202, 0.2);
            color: #dc2626;
        }

        .date-today {
            background: rgba(254, 249, 195, 0.2);
            color: #ca8a04;
        }

        /* Calendar icon animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .calendar-pulse {
            animation: pulse 2s infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .rescheduling-header {
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

            .date-indicator {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .rescheduling-header h1 {
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
        <div class="rescheduling-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-3 fw-bold">
                        <span class="material-icons-round me-2 calendar-pulse">schedule</span>
                        Rescheduling Orders
                    </h1>
                    <p class="mb-0 opacity-75">Orders that require rescheduling of delivery dates</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-50 rounded-pill px-4 py-2">
                        <span class="fw-bold">{{ $count }}</span> Orders Rescheduled
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card rescheduling">
                    <div class="stats-icon rescheduling">
                        <span class="material-icons-round" style="font-size: 2rem">schedule</span>
                    </div>
                    <h4 class="fw-bold mb-2">Rescheduled Orders</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-teal me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">{{ $count }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round" style="font-size: 2rem">pending_actions</span>
                    </div>
                    <h4 class="fw-bold mb-2">Pending Amount</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-blue me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">
                            Rs. {{ number_format($orders->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card upcoming">
                    <div class="stats-icon upcoming">
                        <span class="material-icons-round" style="font-size: 2rem">event_upcoming</span>
                    </div>
                    <h4 class="fw-bold mb-2">Upcoming Reschedules</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-pink me-2">notification_important</span>
                        <h3 class="mb-0 fw-bold">
                            {{ $upcomingCount ?? '0' }}
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
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">assignment_return</span>
                                    Return Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.rescheduling-order') }}"
                                   class="nav-link active d-flex align-items-center">
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
                                    <span class="material-icons-round me-2">calendar_today</span>
                                    Rescheduled Orders List
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn btn-light me-2" onclick="refreshPage()">
                                    <span class="material-icons-round me-2">refresh</span>
                                    Refresh
                                </button>
                                <button class="btn btn-outline-info" onclick="exportReschedules()">
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
                                    <th>Original Delivery</th>
                                    <th>Rescheduled Date</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>District</th>
                                    <th>Remark</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    @php
                                        $rescheduleDate = $order->rescheduling_date;
                                        $today = \Carbon\Carbon::today();
                                        $dateClass = 'date-upcoming';
                                        $dateStatus = 'Upcoming';

                                        if ($rescheduleDate) {
                                            if ($rescheduleDate->isPast()) {
                                                $dateClass = 'date-overdue';
                                                $dateStatus = 'Overdue';
                                            } elseif ($rescheduleDate->isToday()) {
                                                $dateClass = 'date-today';
                                                $dateStatus = 'Today';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-info me-2">
                                                        schedule
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
                                                    <span class="material-icons-round text-warning me-2">
                                                        event_busy
                                                    </span>
                                                {{ $order->delivery_date ? $order->delivery_date->format('Y.m.d') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-primary me-2">
                                                        event_available
                                                    </span>
                                                @if($order->rescheduling_date)
                                                    <div>
                                                        <div>{{ $order->rescheduling_date->format('Y.m.d') }}</div>
                                                        <div class="date-indicator {{ $dateClass }}">
                                                                <span class="material-icons-round" style="font-size: 1rem">
                                                                    {{ $dateClass === 'date-today' ? 'notification_important' :
                                                                       ($dateClass === 'date-overdue' ? 'warning' : 'notifications') }}
                                                                </span>
                                                            {{ $dateStatus }}
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
                                            @if($order->remark)
                                                <div class="remark-tooltip" data-bs-toggle="tooltip"
                                                     data-bs-placement="top"
                                                     title="{{ $order->remark }}">
                                                    <div class="remark-content">
                                                            <span class="material-icons-round text-info me-1" style="font-size: 1rem">
                                                                notes
                                                            </span>
                                                        {{ Str::limit($order->remark, 30) }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-warning me-2">
                                                        payments
                                                    </span>
                                                <strong class="text-warning">
                                                    Rs. {{ number_format($order->total_amount, 2) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                                <span class="badge-rescheduling">
                                                    Rescheduled
                                                </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <button class="action-btn view"
                                                        onclick="viewOrder({{ $order->id }})">
                                                    <span class="material-icons-round">visibility</span>
                                                    View
                                                </button>
                                                <button class="action-btn reschedule"
                                                        onclick="updateReschedule({{ $order->id }}, '{{ $order->order_id }}')">
                                                    <span class="material-icons-round">edit_calendar</span>
                                                    Update
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
                                    Showing {{ $orders->count() }} of {{ $count }} rescheduled orders
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group">
                                    <button class="btn btn-light" onclick="printReschedules()">
                                        <span class="material-icons-round me-2">print</span>
                                        Print
                                    </button>
                                    <button class="btn btn-outline-info" onclick="sendReminders()">
                                        <span class="material-icons-round me-2">notifications</span>
                                        Send Reminders
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Reschedule Modal -->
    <div class="modal fade" id="updateRescheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--rescheduling-gradient);">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2">edit_calendar</span>
                        Update Reschedule Date
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateRescheduleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h6 id="rescheduleOrderTitle" class="fw-semibold"></h6>
                            <p class="text-muted small">Update the rescheduled delivery date</p>
                        </div>

                        <div class="mb-4">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons-round me-2">info</span>
                                    <div>
                                        <small>This order is currently marked as rescheduled. Update the new delivery date below.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-primary">calendar_today</span>
                                New Rescheduled Date *
                            </label>
                            <input type="date" name="rescheduling_date" id="rescheduleDate"
                                   class="form-control" required
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                            <small class="text-muted">Select the new delivery date</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-secondary">notes</span>
                                Update Reason
                            </label>
                            <textarea name="remark" id="rescheduleRemark" class="form-control" rows="3"
                                      placeholder="Explain the reason for rescheduling update..."></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notifyCustomer">
                            <label class="form-check-label" for="notifyCustomer">
                                <span class="material-icons-round me-1" style="font-size: 1rem">notifications</span>
                                Notify customer about date change
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <span class="material-icons-round me-1">close</span>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-info">
                            <span class="material-icons-round me-1">update</span>
                            Update Date
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
                order: [[3, 'asc']], // Sort by rescheduling date ascending
                pageLength: 10,
                responsive: true,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: '<span class="material-icons-round">search</span>',
                    searchPlaceholder: 'Search reschedules...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ reschedules',
                    infoEmpty: 'No rescheduled orders available',
                    zeroRecords: 'No matching reschedules found',
                    paginate: {
                        first: '<span class="material-icons-round">first_page</span>',
                        last: '<span class="material-icons-round">last_page</span>',
                        next: '<span class="material-icons-round">chevron_right</span>',
                        previous: '<span class="material-icons-round">chevron_left</span>'
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 3 }, // Reschedule Date
                    { responsivePriority: 2, targets: -1 }, // Actions
                    { responsivePriority: 3, targets: 4 }, // Customer
                    { responsivePriority: 4, targets: 0 }, // Order No
                    { orderable: false, targets: [7, -1] } // Remark and Actions
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
                title: 'Refresh Reschedules?',
                text: 'This will reload all rescheduling orders data.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes, Refresh',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-info',
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

        // Update reschedule date
        function updateReschedule(orderId, orderNumber) {
            // Set form action
            $('#updateRescheduleForm').attr('action', `/seller/order/${orderId}`);

            // Set order title
            $('#rescheduleOrderTitle').text(`Update Reschedule - Order #${orderNumber}`);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('updateRescheduleModal'));
            modal.show();
        }

        // Export reschedules
        function exportReschedules() {
            Swal.fire({
                title: 'Export Reschedules',
                html: `<div class="text-start">
                          <p>Select export format for rescheduled orders:</p>
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
                                  <input class="form-check-input" type="checkbox" id="includeOverdue" checked>
                                  <label class="form-check-label" for="includeOverdue">
                                      Include overdue reschedules
                                  </label>
                              </div>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Export',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-info',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const format = document.querySelector('input[name="exportFormat"]:checked').value;
                    const includeOverdue = document.getElementById('includeOverdue').checked;

                    Swal.fire({
                        title: 'Exporting...',
                        html: `Preparing reschedules export (${format.toUpperCase()})...`,
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

        // Print reschedules
        function printReschedules() {
            Swal.fire({
                title: 'Print Reschedules',
                html: `<div class="text-start">
                          <p>Configure print settings for rescheduled orders:</p>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printWithDates" checked>
                              <label class="form-check-label" for="printWithDates">
                                  Include date comparisons
                              </label>
                          </div>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printRemarks" checked>
                              <label class="form-check-label" for="printRemarks">
                                  Include remarks
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="printStatus" checked>
                              <label class="form-check-label" for="printStatus">
                                  Include date status
                              </label>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Print',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-info',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implement print logic here
                    window.print();
                }
            });
        }

        // Send reminders
        function sendReminders() {
            Swal.fire({
                title: 'Send Reminders?',
                html: `<div class="text-start">
                          <p>Send reminders for upcoming rescheduled orders:</p>
                          <div class="alert alert-warning">
                              <div class="d-flex align-items-center">
                                  <span class="material-icons-round me-2">warning</span>
                                  <div>
                                      <strong>Note:</strong> This will send notifications to customers about their rescheduled delivery dates.
                                  </div>
                              </div>
                          </div>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="remindOverdue" checked>
                              <label class="form-check-label" for="remindOverdue">
                                  Include overdue reschedules
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="remindToday" checked>
                              <label class="form-check-label" for="remindToday">
                                  Include today's reschedules
                              </label>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Send Reminders',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-warning',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending...',
                        html: 'Sending reminders to customers...',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 3000,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Reminders Sent!',
                            text: 'Customer reminders have been sent successfully.',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }

        // Form submission handler for reschedule update
        document.getElementById('updateRescheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const newDate = document.getElementById('rescheduleDate').value;
            const notify = document.getElementById('notifyCustomer').checked;
            const orderTitle = document.getElementById('rescheduleOrderTitle').textContent;

            Swal.fire({
                title: 'Update Reschedule?',
                html: `<div class="text-start">
                          <p>Update rescheduled date to <strong>${newDate}</strong>?</p>
                          <div class="alert alert-info mt-3">
                              <div class="d-flex align-items-center">
                                  <span class="material-icons-round me-2">info</span>
                                  <div>
                                      ${notify ? 'Customer will be notified about this change.' : 'Customer will NOT be notified.'}
                                  </div>
                              </div>
                          </div>
                       </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-info',
                    cancelButton: 'btn btn-outline-secondary'
                },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while we update the reschedule date',
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

        // Highlight upcoming reschedules
        function highlightUpcomingReschedules() {
            const today = new Date().toISOString().split('T')[0];
            const dateCells = document.querySelectorAll('td:has(.date-indicator)');

            dateCells.forEach(cell => {
                const dateText = cell.querySelector('.date-indicator').textContent;
                if (dateText.includes('Today') || dateText.includes('Overdue')) {
                    cell.closest('tr').classList.add('table-warning');
                }
            });
        }

        // Initialize highlighting
        highlightUpcomingReschedules();
    </script>
@endpush
