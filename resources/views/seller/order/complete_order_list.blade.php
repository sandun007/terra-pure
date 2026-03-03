@extends('seller.layout.app')

@push('title')
    Complete Orders
@endpush

@push('css')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --secondary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
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
        .complete-order-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .complete-order-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .complete-order-header .order-count {
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stats-card.primary { border-left-color: #667eea; }
        .stats-card.success { border-left-color: #43e97b; }
        .stats-card.info { border-left-color: #2575fc; }

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

        .stats-icon.primary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .stats-icon.success {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
        }

        .stats-icon.info {
            background: rgba(41, 117, 252, 0.1);
            color: #2575fc;
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
            background: var(--primary-gradient);
            color: white;
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

        table.dataTable tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.03);
        }

        /* Badges */
        .badge-complete {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-platform {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
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

        .action-btn.delete {
            background: var(--danger);
            color: white;
        }

        .action-btn.print {
            background: var(--info);
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

        /* Product cell styling */
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
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .complete-order-header {
                padding: 1.5rem;
                text-align: center;
            }

            .complete-order-header h1 {
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
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header Section -->
        <div class="complete-order-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="vertical-align: middle; font-size: 32px;">check_circle</span>
                        Complete Orders
                    </h1>
                    <p class="mb-0 opacity-75">Successfully delivered and completed orders</p>
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
                <div class="stats-card primary">
                    <div class="stats-icon primary">
                        <span class="material-icons-round">receipt_long</span>
                    </div>
                    <h6 class="text-muted mb-1">Total Completed</h6>
                    <h2 class="fw-bold mb-0">{{ $count }}</h2>
                    <small class="text-muted">Orders delivered successfully</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card success">
                    <div class="stats-icon success">
                        <span class="material-icons-round">attach_money</span>
                    </div>
                    <h6 class="text-muted mb-1">Total Revenue</h6>
                    <h2 class="fw-bold mb-0">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                    <small class="text-muted">From completed orders</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card info">
                    <div class="stats-icon info">
                        <span class="material-icons-round">calendar_today</span>
                    </div>
                    <h6 class="text-muted mb-1">Latest Delivery</h6>
                    <h2 class="fw-bold mb-0">
                        @if($orders->first())
                            {{ $orders->first()->delivery_date ? \Carbon\Carbon::parse($orders->first()->delivery_date)->format('M d, Y') : 'N/A' }}
                        @else
                            N/A
                        @endif
                    </h2>
                    <small class="text-muted">Most recent completion</small>
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
                            Completed Orders List
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-light me-2" onclick="refreshPage()">
                            <span class="material-icons-round me-2" style="font-size: 18px;">refresh</span>
                            Refresh
                        </button>
                        <button class="btn btn-outline-primary" id="exportBtn">
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
                                <th>Amount</th>
                                <th>Order Date</th>
                                <th>Delivery Date</th>
                                <th>Platform</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-success me-2">receipt</span>
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
                                        <div>
                                            <div class="fw-bold text-success">Rs. {{ number_format($order->total_amount, 2) }}</div>
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
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-success me-2">delivery_dining</span>
                                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-platform">
                                            {{ $order->platform ?? 'Manual' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <!-- View Details Button -->
                                            <button onclick="viewOrder({{ $order->id }})"
                                                    class="action-btn view"
                                                    title="View Order Details">
                                                <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                            </button>

                                            <!-- Print Invoice Button -->
                                            <button onclick="printInvoice({{ $order->id }})"
                                                    class="action-btn print"
                                                    title="Print Invoice">
                                                <span class="material-icons-round" style="font-size: 18px;">print</span>
                                            </button>

                                            <!-- Edit/Update Button -->
                                            <button onclick="editOrder({{ $order->id }})"
                                                    class="action-btn edit"
                                                    title="Update Order">
                                                <span class="material-icons-round" style="font-size: 18px;">edit</span>
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
                            <h5>No Completed Orders</h5>
                            <p>You don't have any completed orders at the moment.</p>
                            <a href="{{ route('seller.pending-order') }}" class="btn btn-primary mt-3">
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
                            Showing {{ $orders->count() }} of {{ $count }} completed orders
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
                <div class="modal-header" style="background: var(--primary-gradient); color: white;">
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
                                <option value="complete" selected>Complete</option>
                                <option value="return">Return</option>
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
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            @if($count > 0)
            // Initialize DataTable
            $('#ordersTable').DataTable({
                order: [[6, 'desc']], // Sort by delivery date descending
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search completed orders...",
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
                    { orderable: false, targets: [1, 8] }, // Disable sorting on Product and Actions columns
                    { responsivePriority: 1, targets: 0 }, // Order ID
                    { responsivePriority: 2, targets: 2 }, // Customer
                    { responsivePriority: 3, targets: 4 }, // Amount
                    { responsivePriority: 4, targets: 6 }, // Delivery Date
                ]
            });
            @endif

            // Export button functionality
            $('#exportBtn').on('click', function() {
                Swal.fire({
                    title: 'Export Options',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <label class="form-label">Export Format:</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="excel" value="excel" checked>
                                    <label class="form-check-label d-flex align-items-center" for="excel">
                                        <span class="material-icons-round me-2 text-success">description</span>
                                        Excel (.xlsx)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="csv" value="csv">
                                    <label class="form-check-label d-flex align-items-center" for="csv">
                                        <span class="material-icons-round me-2 text-info">table_chart</span>
                                        CSV (.csv)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="pdf" value="pdf">
                                    <label class="form-check-label d-flex align-items-center" for="pdf">
                                        <span class="material-icons-round me-2 text-danger">picture_as_pdf</span>
                                        PDF (.pdf)
                                    </label>
                                </div>
                            </div>
                        </div>
                    `,
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
                        exportData(format);
                    }
                });
            });
        });

        // View Order Details
        function viewOrder(id) {
            window.location.href = `/seller/order/show/${id}`;
        }

        // Print Invoice
        function printInvoice(id) {
            window.open(`/seller/order/print/${id}`, '_blank');
        }

        // Edit/Update Order
        function editOrder(id) {
            $('#updateForm').attr('action', `/seller/order/update/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();
        }

        // Refresh Page with confirmation
        function refreshPage() {
            Swal.fire({
                title: 'Refresh Page?',
                text: 'This will reload all completed orders data.',
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

        // Export data function
        function exportData(format) {
            Swal.fire({
                title: 'Preparing Export',
                html: 'Please wait while we prepare your file...',
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate export process
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Complete',
                    text: `Your ${format.toUpperCase()} file is ready for download`,
                    confirmButtonText: 'Download',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }, 2000);
        }

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Status change handler for modal
        document.getElementById('statusSelect')?.addEventListener('change', function() {
            // You can add logic here if needed
        });
    </script>
@endpush
