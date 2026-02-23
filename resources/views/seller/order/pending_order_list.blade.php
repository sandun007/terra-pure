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
        .badge-call-status  { background:rgba(59,130,246,.1); color:var(--info);    padding:4px 12px; border-radius:999px; font-size:11px; font-weight:600; }

        .action-btn { width:36px; height:36px; border-radius:8px; border:none; display:inline-flex; align-items:center; justify-content:center; transition:all .2s; cursor:pointer; }
        .action-btn.view   { background:var(--primary); color:white; }
        .action-btn.edit   { background:var(--warning);  color:white; }
        .action-btn.delete { background:var(--danger);   color:white; }
        .action-btn:hover  { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }

        .alert { border-radius:12px; padding:1rem 1.25rem; border:none; display:flex; align-items:center; gap:12px; animation:slideDown .3s ease-out; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success { background:#d1fae5; color:#065f46; }
        .alert-danger  { background:#fee2e2; color:#991b1b; }

        .modal-content { border-radius:16px; border:none; box-shadow:0 20px 50px rgba(0,0,0,.15); }
        .modal-header  { background:var(--primary); color:white; border-radius:16px 16px 0 0; padding:1.5rem; border-bottom:none; }
        .modal-body    { padding:2rem; }
        .form-control, .form-select { border-radius:8px; border:2px solid var(--border); padding:.75rem 1rem; transition:all .3s; }
        .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }

        .btn { padding:10px 20px; border-radius:8px; font-weight:600; transition:all .2s; border:none; cursor:pointer; }
        .btn-primary   { background:var(--primary); color:white; }
        .btn-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }
        .btn-secondary { background:var(--mid); color:white; }

        .search-wrapper { position:relative; display:inline-block; width:100%; max-width:400px; }
        .search-input   { width:100%; padding:10px 16px 10px 40px; border:2px solid var(--border); border-radius:8px; font-size:14px; transition:all .3s; }
        .search-input:focus { border-color:var(--primary); outline:none; box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--mid); }

        .empty-state { text-align:center; padding:60px 20px; }
        .empty-icon  { font-size:64px; color:var(--border); margin-bottom:16px; }

        @media(max-width:768px){
            .pending-header { padding:1.5rem; }
            .pending-header h1 { font-size:22px; }
            .nav-tabs .nav-link { padding:10px 16px; font-size:13px; }
            .action-btn { width:32px; height:32px; }
            .table { font-size:13px; }
            .table thead th, .table tbody td { padding:.75rem .5rem; }
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

            @if($count > 0)
                <!-- Search Box -->
                <div class="search-box pt-3">
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
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Platform</th>
                                <th>Call Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
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

    <script>
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
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#ordersTableBody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status change handler
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
