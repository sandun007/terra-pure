@extends('wholesaler.layouts.app')

@push('title')
    Complete Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
        }

        body { font-family: 'DM Sans', sans-serif; background: #f8fafc; }

        .complete-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; padding: 2rem; border-radius: 16px; margin-bottom: 2rem;
        }

        .nav-tabs {
            border: none; background: white; border-radius: 12px; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .nav-tabs .nav-link {
            border: none; font-weight: 600; color: var(--mid); padding: 12px 24px;
            border-radius: 8px; margin-right: 4px; transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover { background: rgba(79, 70, 229, 0.1); color: var(--primary); }
        .nav-tabs .nav-link.active { background: var(--success); color: white; }

        .card { border: none; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        table.dataTable tbody td { padding: 1rem; vertical-align: middle; }

        .badge-status { padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge-complete { background: #d1fae5; color: #065f46; }

        .action-btn {
            width: 36px; height: 36px; border-radius: 8px; border: none;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; cursor: pointer;
        }
        .action-btn.view { background: var(--primary); color: white; }
        .action-btn.edit { background: var(--warning); color: white; }
        .action-btn.print { background: var(--info); color: white; }
        .action-btn.delete { background: var(--danger); color: white; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        /* Modal Styles */
        .modal-content { border-radius: 16px; border: none; }
        .modal-header { background: var(--success); color: white; border-radius: 16px 16px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-body { padding: 2rem; }
        .form-control, .form-select { border-radius: 8px; border: 2px solid var(--border); padding: 0.75rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--success); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        .btn-primary { background: var(--success); border: none; }
        .btn-primary:hover { background: #059669; }

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
        <div class="complete-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><span class="material-icons-round me-2" style="vertical-align: middle;">check_circle</span>Complete Orders</h1>
                    <p class="mb-0 opacity-75">Successfully delivered orders</p>
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

        <div class="mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a href="{{ route('wholesaler.order.pending-order') }}" class="nav-link">Pending</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.complete-order') }}" class="nav-link active">Complete</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.return-order') }}" class="nav-link">Return</a></li>
                <li class="nav-item"><a href="{{ route('wholesaler.order.rescheduling-order') }}" class="nav-link">Rescheduling</a></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Completed Orders List</h5>
                <a href="{{ route('wholesaler.order.new') }}" class="btn btn-primary btn-sm">
                    <span class="material-icons-round me-1" style="font-size: 16px;">add</span>New Order
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
                                <th>Order Date</th>
                                <th>Delivery Date</th>
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
                                    <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('wholesaler.order.show', $order->id) }}" class="action-btn view" title="View">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }}, {{ json_encode([
                                                    'status' => $order->status,
                                                    'remark' => $order->remark,
                                                    'delivery_date' => $order->delivery_date
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
                <div class="card-body text-center py-5">
                    <span class="material-icons-round" style="font-size: 64px; color: var(--border);">inbox</span>
                    <h5>No Completed Orders</h5>
                    <p class="text-muted">You don't have any completed orders yet.</p>
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
                        Update Completed Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" method="POST" onsubmit="return submitUpdateForm(event)">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="info-note">
                            <i class="material-icons-round" style="font-size: 16px; vertical-align: middle;">info</i>
                            <strong>Note:</strong> Changing status will move this order to the relevant page.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order Status <span class="text-danger">*</span></label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="complete">✅ Complete</option>
                                <option value="pending">⏳ Pending</option>
                                <option value="return">🔄 Return</option>
                                <option value="rescheduling">📅 Rescheduling</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Delivery Date</label>
                            <input type="date" name="delivery_date" id="deliveryDateField" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark / Notes</label>
                            <textarea name="remark" id="remarkField" class="form-control" rows="3" placeholder="Add notes about this order..."></textarea>
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
                order: [[6, 'desc']],
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

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut('slow', function() { $(this).remove(); });
            }, 5000);
        });

        function editOrder(id, currentData) {
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            document.getElementById('updateForm').action = `/wholesaler/order/update/${id}`;

            // Set form values
            document.getElementById('statusSelect').value = currentData.status || 'complete';
            document.getElementById('remarkField').value = currentData.remark || '';

            // Set date field if it exists
            if (currentData.delivery_date) {
                document.getElementById('deliveryDateField').value = currentData.delivery_date;
            } else {
                // Set default to today if no delivery date
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('deliveryDateField').value = today;
            }

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
                            window.location.href = data.redirect_url || '{{ route('wholesaler.order.complete-order') }}';
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
                    // Create and submit delete form
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
