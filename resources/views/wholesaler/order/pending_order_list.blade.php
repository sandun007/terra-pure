@extends('wholesaler.layouts.app')

@push('title')
    Pending Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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

        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .pending-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 2rem; border-radius: 16px; margin-bottom: 2rem;
        }

        .stats-card {
            background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease; border-left: 4px solid var(--primary);
        }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }

        .nav-tabs {
            border: none; background: white; border-radius: 12px; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .nav-tabs .nav-link {
            border: none; font-weight: 600; color: var(--mid); padding: 12px 24px;
            border-radius: 8px; margin-right: 4px; transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover { background: rgba(79, 70, 229, 0.1); color: var(--primary); }
        .nav-tabs .nav-link.active { background: var(--primary); color: white; }

        .card { border: none; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        table.dataTable tbody td { padding: 1rem; vertical-align: middle; }

        .badge-status { padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-complete { background: #d1fae5; color: #065f46; }
        .badge-return { background: #fee2e2; color: #991b1b; }
        .badge-rescheduling { background: #dbeafe; color: #1e40af; }

        .action-btn {
            width: 36px; height: 36px; border-radius: 8px; border: none;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; cursor: pointer;
        }
        .action-btn.view { background: var(--primary); color: white; }
        .action-btn.edit { background: var(--warning); color: white; }
        .action-btn.delete { background: var(--danger); color: white; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        /* Modal Styles */
        .modal-content { border-radius: 16px; border: none; }
        .modal-header { background: var(--primary); color: white; border-radius: 16px 16px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-body { padding: 2rem; }
        .form-control, .form-select { border-radius: 8px; border: 2px solid var(--border); padding: 0.75rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: var(--primary-dark); }

        .empty-state { text-align: center; padding: 60px 20px; }

        .info-note {
            background: #f0f9ff;
            border-left: 4px solid var(--info);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            color: #0369a1;
            margin-bottom: 15px;
        }
        .info-note i { vertical-align: middle; margin-right: 5px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <div class="pending-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><span class="material-icons-round me-2" style="vertical-align: middle;">pending_actions</span>Pending Orders</h1>
                    <p class="mb-0 opacity-75">Manage and track your pending orders</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="order-count bg-white bg-opacity-25 rounded-pill d-inline-block px-4 py-2">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">shopping_bag</span>
                        {{ $count }} {{ $count === 1 ? 'Order' : 'Orders' }}
                    </div>
                </div>
            </div>
        </div>

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
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-warning me-3" style="font-size: 32px;">pending_actions</span>
                        <div>
                            <h6 class="text-muted mb-1">Total Pending</h6>
                            <h2 class="fw-bold mb-0">{{ $count }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-primary me-3" style="font-size: 32px;">payments</span>
                        <div>
                            <h6 class="text-muted mb-1">Pending Amount</h6>
                            <h2 class="fw-bold mb-0">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-info me-3" style="font-size: 32px;">schedule</span>
                        <div>
                            <h6 class="text-muted mb-1">Latest Order</h6>
                            <h2 class="fw-bold mb-0">
                                @if($orders->first())
                                    {{ \Carbon\Carbon::parse($orders->first()->order_date)->format('M d') }}
                                @else
                                    N/A
                                @endif
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a href="{{ route('wholesaler.order.pending-order') }}" class="nav-link active">Pending</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.complete-order') }}" class="nav-link">Complete</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.return-order') }}" class="nav-link">Return</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.rescheduling-order') }}" class="nav-link">Rescheduling</a></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <span class="material-icons-round me-2" style="vertical-align: middle;">list_alt</span>
                    Orders List
                </h5>
                <a href="{{ route('wholesaler.order.new') }}" class="btn btn-primary">
                    <span class="material-icons-round me-2">add</span>New Order
                </a>
            </div>

            @if($count > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="ordersTable">
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Platform</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div>{{ $order->product_name }}</div>
                                        <small class="text-muted">{{ $order->product_code }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $order->platform ?? 'Manual' }}</span></td>
                                    <td><span class="badge-status badge-pending">Pending</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('wholesaler.order.show', $order->id) }}" class="action-btn view" title="View">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }}, {{ json_encode([
                                                    'status' => $order->status,
                                                    'remark' => $order->remark,
                                                    'delivery_date' => $order->delivery_date,
                                                    'rescheduling_date' => $order->rescheduling_date,
                                                    'return_date' => $order->return_date
                                                ]) }})" class="action-btn edit" title="Edit">
                                                <span class="material-icons-round">edit</span>
                                            </button>
                                            <button onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')" class="action-btn delete" title="Delete">
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
            @else
                <div class="card-body">
                    <div class="empty-state">
                        <span class="material-icons-round" style="font-size: 64px; color: var(--border);">inbox</span>
                        <h5>No Pending Orders</h5>
                        <p class="text-muted">You don't have any pending orders at the moment.</p>
                        <a href="{{ route('wholesaler.order.new') }}" class="btn btn-primary mt-3">Create New Order</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Update Order Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <span class="material-icons-round me-2">edit</span>
                        Update Order Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" method="POST" onsubmit="return submitUpdateForm(event)">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="info-note">
                            <i class="material-icons-round" style="font-size: 16px; vertical-align: middle;">info</i>
                            <strong>Note:</strong> Changing status will move this order to the relevant page automatically.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order Status <span class="text-danger">*</span></label>
                            <select name="status" id="statusSelect" class="form-select" required onchange="toggleDateFields()">
                                <option value="pending">⏳ Pending</option>
                                <option value="complete">✅ Complete</option>
                                <option value="return">🔄 Return</option>
                                <option value="rescheduling">📅 Rescheduling</option>
                            </select>
                            <small class="text-muted">This determines which list the order appears in</small>
                        </div>

                        <div class="mb-3" id="deliveryDateField" style="display:none;">
                            <label class="form-label fw-semibold">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3" id="reschedulingDateField" style="display:none;">
                            <label class="form-label fw-semibold">Rescheduling Date</label>
                            <input type="date" name="rescheduling_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                        </div>

                        <div class="mb-3" id="returnDateField" style="display:none;">
                            <label class="form-label fw-semibold">Return Date</label>
                            <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark / Notes</label>
                            <textarea name="remark" id="remarkField" class="form-control" rows="3" placeholder="Add notes about this order..."></textarea>
                        </div>

                        <div class="alert alert-info py-2 px-3 mb-0">
                            <i class="material-icons-round" style="font-size: 14px; vertical-align: middle;">sync_alt</i>
                            <small>After update, you'll be redirected to the appropriate page based on the selected status.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="material-icons-round me-1" style="font-size: 16px;">save</span>
                            Update Order
                        </button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            @if($count > 0)
            $('#ordersTable').DataTable({
                order: [[5, 'desc']],
                pageLength: 25,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search orders...",
                    lengthMenu: "Show _MENU_ orders",
                    info: "Showing _START_ to _END_ of _TOTAL_ orders",
                    zeroRecords: "No matching orders found",
                }
            });
            @endif

            setTimeout(() => {
                $('.alert').fadeOut('slow', function() { $(this).remove(); });
            }, 5000);
        });

        function toggleDateFields() {
            const status = document.getElementById('statusSelect').value;
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
        }

        function editOrder(id, currentData) {
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            document.getElementById('updateForm').action = `/wholesaler/order/update/${id}`;

            // Set form values
            document.getElementById('statusSelect').value = currentData.status || 'pending';
            document.getElementById('remarkField').value = currentData.remark || '';

            // Set date fields if they exist
            if (currentData.delivery_date) {
                document.querySelector('input[name="delivery_date"]').value = currentData.delivery_date;
            }
            if (currentData.rescheduling_date) {
                document.querySelector('input[name="rescheduling_date"]').value = currentData.rescheduling_date;
            }
            if (currentData.return_date) {
                document.querySelector('input[name="return_date"]').value = currentData.return_date;
            }

            toggleDateFields();
            modal.show();
        }

        function submitUpdateForm(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
            submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.message
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });

            return false;
        }

        function deleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Are you sure you want to delete order <strong>${orderNumber}</strong>?<br><br>
                       <div class="alert alert-warning py-2 px-3 mb-0">
                           <small>⚠️ This will restore product stock and cannot be undone.</small>
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
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
