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
        .pending-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .pending-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .pending-header .order-count {
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

        .stats-card.pending { border-left-color: var(--warning); }
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

        .stats-icon.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
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
            background: var(--primary);
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
            background-color: rgba(79, 70, 229, 0.03);
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

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
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

        .action-btn.delete {
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
            background: var(--primary);
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
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pending-header {
                padding: 1.5rem;
            }

            .pending-header h1 {
                font-size: 22px;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 10px 16px;
                font-size: 13px;
            }

            .action-btn {
                width: 32px;
                height: 32px;
            }

            .product-cell {
                flex-direction: column;
                align-items: flex-start;
            }
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

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card pending">
                    <div class="stats-icon pending">
                        <span class="material-icons-round">pending_actions</span>
                    </div>
                    <h6 class="text-muted mb-1">Total Pending</h6>
                    <h2 class="fw-bold mb-0">{{ $count }}</h2>
                    <small class="text-muted">Orders awaiting processing</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card amount">
                    <div class="stats-icon amount">
                        <span class="material-icons-round">payments</span>
                    </div>
                    <h6 class="text-muted mb-1">Pending Amount</h6>
                    <h2 class="fw-bold mb-0">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                    <small class="text-muted">Total value of pending orders</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card recent">
                    <div class="stats-icon recent">
                        <span class="material-icons-round">schedule</span>
                    </div>
                    <h6 class="text-muted mb-1">Latest Order</h6>
                    <h2 class="fw-bold mb-0">
                        @if($orders->first())
                            {{ \Carbon\Carbon::parse($orders->first()->order_date)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </h2>
                    <small class="text-muted">Most recent pending order</small>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('seller.pending-order') }}" class="nav-link active">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">pending_actions</span>
                        Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.complete-order') }}" class="nav-link">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">check_circle</span>
                        Complete
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.return-order') }}" class="nav-link">
                        <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">assignment_return</span>
                        Return
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.rescheduling-order') }}" class="nav-link">
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
                            Orders List
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
                            <th>Date</th>
                            <th>Platform</th>
                            <th>Call Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
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
                                <td>
                                        <span class="badge-call-status">
                                            {{ $order->call_status ?? 'Pending Call' }}
                                        </span>
                                </td>
                                <td>
                                        <span class="badge-status badge-pending">
                                            <span class="material-icons-round me-1" style="font-size: 12px; vertical-align: middle;">schedule</span>
                                            Pending
                                        </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button onclick="viewOrder({{ $order->id }})"
                                                class="action-btn view"
                                                title="View Details">
                                            <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                        </button>
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
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <span class="material-icons-round mb-3" style="font-size: 64px; opacity: 0.3;">inbox</span>
                                        <h5>No Pending Orders</h5>
                                        <p>You don't have any pending orders at the moment.</p>
                                        <a href="{{ route('seller.new-order') }}" class="btn btn-primary mt-3">
                                            <span class="material-icons-round me-2" style="font-size: 18px; vertical-align: middle;">add</span>
                                            Create New Order
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            @if($count > 0)
            // Initialize DataTable only if there are orders
            $('#ordersTable').DataTable({
                order: [[5, 'desc']], // Sort by date column
                pageLength: 25,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [1, 9] }, // Disable sorting on Product and Actions columns
                    { responsivePriority: 1, targets: 0 }, // Order ID
                    { responsivePriority: 2, targets: 2 }, // Customer
                    { responsivePriority: 3, targets: 4 }, // Amount
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search orders...",
                    lengthMenu: "Show _MENU_ orders",
                    info: "Showing _START_ to _END_ of _TOTAL_ orders",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
            @endif

            // Status change handler
            $('#statusSelect').on('change', function() {
                const status = $(this).val();
                $('#deliveryDateField, #reschedulingDateField, #returnDateField').hide();

                if (status === 'complete') {
                    $('#deliveryDateField').show();
                } else if (status === 'rescheduling') {
                    $('#reschedulingDateField').show();
                } else if (status === 'return') {
                    $('#returnDateField').show();
                }
            });

            // Auto-hide alerts
            setTimeout(() => {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        function viewOrder(id) {
            window.location.href = `/seller/order/show/${id}`;
        }

        function editOrder(id) {
            $('#updateForm').attr('action', `/seller/order/update/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();
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
    </script>
@endpush
