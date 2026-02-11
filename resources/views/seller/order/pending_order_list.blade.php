@extends('seller.layout.app')
@push('title')
    Pending Orders
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
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --pending-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .pending-header {
            background: var(--pending-gradient);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
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

        .stats-card.pending {
            border-left-color: #f6d365;
        }

        .stats-card.amount {
            border-left-color: #667eea;
        }

        .stats-card.recent {
            border-left-color: #f5576c;
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

        .stats-icon.pending {
            background: rgba(246, 211, 101, 0.1);
            color: #f6d365;
        }

        .stats-icon.amount {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .stats-icon.recent {
            background: rgba(245, 87, 108, 0.1);
            color: #f5576c;
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
            background: rgba(102, 126, 234, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            border: none;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #333;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-platform {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .action-btn.view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .action-btn.edit {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #333;
        }

        .action-btn.delete {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
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
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
            border-bottom: none;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #333;
        }

        .status-complete {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .status-return {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            color: white;
        }

        .status-rescheduling {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pending-header {
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
            }

            table.dataTable thead th,
            table.dataTable tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }

            .modal-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .pending-header h1 {
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

            .modal-dialog {
                margin: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header Section -->
        <div class="pending-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-3 fw-bold">
                        <span class="material-icons-round me-2">pending_actions</span>
                        Pending Orders
                    </h1>
                    <p class="mb-0 opacity-75">Orders awaiting processing and delivery</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-25 rounded-pill px-4 py-2">
                        <span class="fw-bold">{{ $count }}</span> Orders Pending
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <span class="material-icons-round me-3">check_circle</span>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <span class="material-icons-round me-3">error</span>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card pending">
                    <div class="stats-icon pending">
                        <span class="material-icons-round" style="font-size: 2rem">pending_actions</span>
                    </div>
                    <h4 class="fw-bold mb-2">Pending Orders</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-warning me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">{{ $count }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round" style="font-size: 2rem">attach_money</span>
                    </div>
                    <h4 class="fw-bold mb-2">Pending Amount</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-primary me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">
                            Rs. {{ number_format($orders->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card recent">
                    <div class="stats-icon recent">
                        <span class="material-icons-round" style="font-size: 2rem">schedule</span>
                    </div>
                    <h4 class="fw-bold mb-2">Recent Order</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-danger me-2">update</span>
                        <div>
                            @if($orders->first())
                                <h5 class="mb-0 fw-bold">{{ $orders->first()->order_date->format('M d, Y') }}</h5>
                            @else
                                <h5 class="mb-0 fw-bold">N/A</h5>
                            @endif
                        </div>
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
                                   class="nav-link active d-flex align-items-center">
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
                                    Pending Orders List
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn btn-light me-2" onclick="refreshPage()">
                                    <span class="material-icons-round me-2">refresh</span>
                                    Refresh
                                </button>
                                <button class="btn btn-outline-primary" onclick="exportOrders()">
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
                                    <th>Platform</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>District</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-warning me-2">
                                                        shopping_bag
                                                    </span>
                                                <strong>{{ $order->order_id }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-info me-2">
                                                        calendar_month
                                                    </span>
                                                {{ $order->order_date->format('Y.m.d') }}
                                            </div>
                                        </td>
                                        <td>
                                                <span class="badge-platform">
                                                    <span class="material-icons-round me-1" style="font-size: 1rem">
                                                        {{ getPlatformIcon($order->platform) }}
                                                    </span>
                                                    {{ $order->platform ?? 'N/A' }}
                                                </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-primary me-2">
                                                        person
                                                    </span>
                                                <span class="text-truncate" style="max-width: 150px">
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
                                                <span class="status-badge status-pending">
                                                    {{ $order->status }}
                                                </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <button class="action-btn view"
                                                        onclick="viewOrder({{ $order->id }})"
                                                        title="View Details">
                                                    <span class="material-icons-round">visibility</span>
                                                </button>
                                                <button class="action-btn edit"
                                                        onclick="openUpdateModal({{ $order->id }})"
                                                        title="Update Status">
                                                    <span class="material-icons-round">edit</span>
                                                </button>
                                                <button class="action-btn delete"
                                                        onclick="deleteOrder({{ $order->id }}, '{{ $order->order_id }}')"
                                                        title="Delete Order">
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
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="text-muted">
                                    Showing {{ $orders->count() }} of {{ $count }} pending orders
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group">
                                    <button class="btn btn-light" onclick="printOrders()">
                                        <span class="material-icons-round me-2">print</span>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2">update</span>
                        Update Order Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h6 id="orderTitle" class="fw-semibold"></h6>
                            <p class="text-muted small">Update the status and provide necessary details</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-primary">category</span>
                                Status *
                            </label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                                <option value="return">Return</option>
                                <option value="rescheduling">Rescheduling</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <span class="material-icons-round me-2 text-secondary">notes</span>
                                Remark
                            </label>
                            <textarea name="remark" class="form-control" rows="3"
                                      placeholder="Add any remarks or notes about this status change..."></textarea>
                        </div>

                        <div id="dateFields">
                            <div class="mb-4 delivery-date" style="display: none;">
                                <label class="form-label fw-semibold">
                                    <span class="material-icons-round me-2 text-success">date_range</span>
                                    Delivery Date
                                </label>
                                <input type="date" name="delivery_date" class="form-control"
                                       value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-4 rescheduling-date" style="display: none;">
                                <label class="form-label fw-semibold">
                                    <span class="material-icons-round me-2 text-info">schedule</span>
                                    Rescheduling Date
                                </label>
                                <input type="date" name="rescheduling_date" class="form-control"
                                       value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-4 return-date" style="display: none;">
                                <label class="form-label fw-semibold">
                                    <span class="material-icons-round me-2 text-danger">assignment_return</span>
                                    Return Date
                                </label>
                                <input type="date" name="return_date" class="form-control"
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <span class="material-icons-round me-1">close</span>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class="material-icons-round me-1">check_circle</span>
                            Update Status
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
                order: [[0, 'desc']],
                pageLength: 10,
                responsive: true,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: '<span class="material-icons-round">search</span>',
                    searchPlaceholder: 'Search orders...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ orders',
                    infoEmpty: 'No orders available',
                    zeroRecords: 'No matching orders found',
                    paginate: {
                        first: '<span class="material-icons-round">first_page</span>',
                        last: '<span class="material-icons-round">last_page</span>',
                        next: '<span class="material-icons-round">chevron_right</span>',
                        previous: '<span class="material-icons-round">chevron_left</span>'
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 },
                    { responsivePriority: 3, targets: 3 },
                    { responsivePriority: 4, targets: 6 },
                    { orderable: false, targets: -1 }
                ]
            });

            // Initialize tooltips
            $('[title]').tooltip();
        });

        // Platform icons helper function
        function getPlatformIcon(platform) {
            const icons = {
                'Facebook': 'facebook',
                'TikTok': 'music_note',
                'Instagram': 'photo_camera',
                'WhatsApp': 'chat',
                'Other': 'public'
            };
            return icons[platform] || 'public';
        }

        // Refresh page with confirmation
        function refreshPage() {
            Swal.fire({
                title: 'Refresh Page?',
                text: 'This will reload all pending orders data.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes, Refresh',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary',
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

        // Open update modal
        function openUpdateModal(orderId) {
            // Set form action
            $('#updateStatusForm').attr('action', `/seller/order/${orderId}`);

            // Get order details (you might want to fetch via AJAX for real data)
            const order = @json($orders->first());
            $('#orderTitle').text(`Update Order #${order.order_id}`);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
            modal.show();
        }

        // Delete order with confirmation
        function deleteOrder(orderId, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `<div class="text-start">
                          <p>Are you sure you want to delete this order?</p>
                          <div class="alert alert-danger mt-3">
                              <strong>Order #${orderNumber}</strong><br>
                              This action cannot be undone.
                          </div>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-secondary'
                },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/seller/order/${orderId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Export orders
        function exportOrders() {
            Swal.fire({
                title: 'Export Orders',
                html: `<div class="text-start">
                          <p>Select export format for pending orders:</p>
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
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Export',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const format = document.querySelector('input[name="exportFormat"]:checked').value;
                    // Implement export logic here
                    Swal.fire({
                        title: 'Export Started',
                        html: `Exporting orders in ${format.toUpperCase()} format...`,
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

        // Print orders
        function printOrders() {
            Swal.fire({
                title: 'Print Orders',
                html: `<div class="text-start">
                          <p>Configure print settings:</p>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printWithDate" checked>
                              <label class="form-check-label" for="printWithDate">
                                  Include current date
                              </label>
                          </div>
                          <div class="form-check mb-3">
                              <input class="form-check-input" type="checkbox" id="printSummary" checked>
                              <label class="form-check-label" for="printSummary">
                                  Include summary
                              </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="printLandscape" checked>
                              <label class="form-check-label" for="printLandscape">
                                  Landscape orientation
                              </label>
                          </div>
                       </div>`,
                showCancelButton: true,
                confirmButtonText: 'Print',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implement print logic here
                    window.print();
                }
            });
        }

        // Status change handler for modal
        document.getElementById('statusSelect').addEventListener('change', function() {
            const status = this.value;
            const dateFields = document.getElementById('dateFields');

            // Hide all date fields
            dateFields.querySelectorAll('.delivery-date, .rescheduling-date, .return-date').forEach(field => {
                field.style.display = 'none';
            });

            // Show relevant date field
            if (status === 'complete') {
                dateFields.querySelector('.delivery-date').style.display = 'block';
            } else if (status === 'rescheduling') {
                dateFields.querySelector('.rescheduling-date').style.display = 'block';
            } else if (status === 'return') {
                dateFields.querySelector('.return-date').style.display = 'block';
            }
        });

        // Form submission handler
        document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const status = document.getElementById('statusSelect').value;

            Swal.fire({
                title: 'Update Status?',
                html: `<div class="text-start">
                          <p>Update order status to <strong>${status}</strong>?</p>
                          <div class="alert alert-info mt-3">
                              This will change the order status and update relevant dates.
                          </div>
                       </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-secondary'
                },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while we update the order status',
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
    </script>
@endpush

    <?php
    function getPlatformIcon($platform) {
        $icons = [
            'Facebook' => 'facebook',
            'TikTok' => 'music_note',
            'Instagram' => 'photo_camera',
            'WhatsApp' => 'chat',
            'Other' => 'public'
        ];
        return $icons[$platform] ?? 'public';
    }
    ?>
