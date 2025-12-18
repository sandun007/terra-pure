@extends('wholesaler.layouts.app')
@push('title')
    Order #{{ $order->order_number }} | Details
@endpush

@push('css')
    <!-- Add Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">

    <style>
        .order-details {
            padding: 30px 0;
        }

        .order-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .order-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-info h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .order-date {
            color: #64748b;
            font-size: 14px;
        }

        .order-status-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .order-status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-confirmed { background: #dbeafe; color: #1d4ed8; }
        .status-processing { background: #f3e8ff; color: #7c3aed; }
        .status-shipped { background: #ecfccb; color: #3f6212; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .order-actions {
            display: flex;
            gap: 10px;
        }

        .btn-order-action {
            padding: 10px 20px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-order-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary-action {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .btn-primary-action:hover {
            background: #4f46e5;
            border-color: #4f46e5;
        }

        .btn-cancel-action {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-cancel-action:hover {
            background: #fca5a5;
            color: #7f1d1d;
            border-color: #fca5a5;
        }

        .btn-cancel-action:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            border-color: #d1d5db;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .order-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }

        .customer-details p,
        .shipping-details p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-label {
            font-weight: 600;
            color: #475569;
            min-width: 100px;
        }

        .detail-value {
            color: #1e293b;
            flex: 1;
        }

        .order-items {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #f8fafc;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            background: #f8fafc;
        }

        .product-name {
            font-weight: 600;
            color: #1e293b;
        }

        .price {
            font-weight: 600;
            color: #6366f1;
        }

        .quantity {
            font-weight: 600;
        }

        .order-summary {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-label {
            color: #64748b;
        }

        .summary-value {
            font-weight: 600;
            color: #1e293b;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }

        .status-timeline {
            margin-top: 30px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #94a3b8;
            border: 3px solid white;
        }

        .timeline-item.active::before {
            background: #6366f1;
        }

        .timeline-item.completed::before {
            background: #10b981;
        }

        .timeline-content {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }

        .timeline-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .timeline-date {
            font-size: 12px;
            color: #64748b;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .status-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
        }

        .status-option {
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-option:hover {
            border-color: #6366f1;
        }

        .status-option.selected {
            background: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        /* Material Icons custom styles */
        .material-icons {
            font-size: 20px;
            vertical-align: middle;
        }

        .material-icons-outlined {
            font-size: 20px;
            vertical-align: middle;
        }

        .btn-order-action .material-icons {
            font-size: 18px;
        }

        .card-title .material-icons {
            font-size: 20px;
            color: #6366f1;
        }

        .detail-value .material-icons {
            font-size: 16px;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .order-meta {
                flex-direction: column;
            }

            .order-status-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .status-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-details">
        <div class="container">
            <!-- Order Header -->
            <div class="order-header">
                <div class="order-meta">
                    <div class="order-info">
                        <h1>Order #{{ $order->order_number }}</h1>
                        <div class="order-date">
                            <i class="material-icons-outlined me-2">calendar_today</i>
                            {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                        </div>
                    </div>

                    <div class="order-status-section">
                        <span class="order-status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>

                        <div class="order-actions">
                            <button class="btn-order-action btn-primary-action" onclick="openStatusModal()">
                                <i class="material-icons-outlined me-2">sync</i>
                                Update Status
                            </button>

                            <!-- Cancel Order Button -->
                            <button class="btn-order-action btn-cancel-action"
                                    onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}')"
                                {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                                <i class="material-icons-outlined me-2">cancel</i>
                                Cancel Order
                            </button>

                            <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                               class="btn-order-action">
                                <i class="material-icons-outlined me-2">download</i>
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="status-timeline">
                    <h5 class="card-title">
                        <i class="material-icons-outlined me-2">history</i>
                        Order Timeline
                    </h5>

                    <div class="timeline">
                        @php
                            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'completed'];
                            $currentStatusIndex = array_search($order->status, $statuses);
                        @endphp

                        @foreach($statuses as $index => $status)
                            <div class="timeline-item
                        {{ $index <= $currentStatusIndex ? 'active' : '' }}
                        {{ $index < $currentStatusIndex ? 'completed' : '' }}">
                                <div class="timeline-content">
                                    <div class="timeline-title">
                                        {{ ucfirst($status) }}
                                    </div>
                                    <div class="timeline-date">
                                        @if($index <= $currentStatusIndex)
                                            @if($index == $currentStatusIndex)
                                                Current Status
                                            @else
                                                Completed on {{ $order->updated_at->format('M d, Y') }}
                                            @endif
                                        @else
                                            Pending
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Cards -->
            <div class="order-cards">
                <!-- Customer Details -->
                <div class="order-card">
                    <h5 class="card-title">
                        <i class="material-icons-outlined me-2">person</i>
                        Customer Details
                    </h5>
                    <div class="customer-details">
                        <p>
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">{{ $order->customer_name }}</span>
                        </p>
                        <p>
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">email</i>
                                {{ $order->customer_email }}
                            </span>
                        </p>
                        <p>
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">phone</i>
                                {{ $order->customer_phone }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Shipping Details -->
                <div class="order-card">
                    <h5 class="card-title">
                        <i class="material-icons-outlined me-2">local_shipping</i>
                        Shipping Details
                    </h5>
                    <div class="shipping-details">
                        <p class="mb-3">
                            <span class="detail-label">Address:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">location_on</i>
                                {{ $order->shipping_address }}
                            </span>
                        </p>
                        <p>
                            <span class="detail-label">Method:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">payments</i>
                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="order-card">
                    <h5 class="card-title">
                        <i class="material-icons-outlined me-2">credit_card</i>
                        Payment Details
                    </h5>
                    <div class="payment-details">
                        <p>
                            <span class="detail-label">Method:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">payment</i>
                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </p>
                        <p>
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    <i class="material-icons-outlined me-1" style="font-size: 14px;">
                                        {{ $order->payment_status == 'paid' ? 'check_circle' : 'pending' }}
                                    </i>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </span>
                        </p>
                        @if($order->notes)
                            <p>
                                <span class="detail-label">Notes:</span>
                                <span class="detail-value">
                                    <i class="material-icons-outlined me-1" style="font-size: 16px;">notes</i>
                                    {{ $order->notes }}
                                </span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items">
                <div class="table-container">
                    <table class="items-table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="material-icons-outlined text-muted">inventory_2</i>
                                        </div>
                                        <div>
                                            <div class="product-name">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: {{ $item->product->code ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="price">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                <td class="quantity">{{ $item->quantity }}</td>
                                <td class="price">Rs. {{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Additional Notes -->
                    @if($order->notes)
                        <div class="order-card">
                            <h5 class="card-title">
                                <i class="material-icons-outlined me-2">sticky_note_2</i>
                                Order Notes
                            </h5>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h5 class="card-title mb-4">Order Summary</h5>

                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">Rs. {{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Tax ({{ $order->tax_rate ?? 5 }}%)</span>
                            <span class="summary-value">Rs. {{ number_format($order->tax, 2) }}</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value">Rs. {{ number_format($order->shipping, 2) }}</span>
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" onclick="closeStatusModal()"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select New Status</label>
                        <div class="status-options">
                            @foreach(['pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                <div class="status-option {{ $order->status == $status ? 'selected' : '' }}"
                                     onclick="selectStatus('{{ $status }}')"
                                     data-status="{{ $status }}">
                                    {{ ucfirst($status) }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="status" id="selectedStatus" value="{{ $order->status }}">
                    </div>

                    <div class="mb-3">
                        <label for="statusNotes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="statusNotes" class="form-control" rows="3"
                                  placeholder="Add notes about this status change..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary flex-fill" onclick="closeStatusModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="material-icons-outlined me-2">save</i>
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Order Confirmation Modal -->
    <div class="modal" id="cancelModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Order Confirmation</h5>
                <button type="button" class="btn-close" onclick="closeCancelModal()"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="material-icons-outlined" style="font-size: 48px; color: #dc2626;">warning</i>
                    <h4 class="mt-3">Cancel Order?</h4>
                    <p class="text-muted">Are you sure you want to cancel order #<span id="cancelOrderNumber"></span>? This action cannot be undone.</p>
                </div>

                <div class="mb-3">
                    <label for="cancelReason" class="form-label fw-bold">Reason for Cancellation</label>
                    <textarea id="cancelReason" class="form-control" rows="3"
                              placeholder="Please provide a reason for cancellation..."></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary flex-fill" onclick="closeCancelModal()">
                        <i class="material-icons-outlined me-2">arrow_back</i>
                        Go Back
                    </button>
                    <button type="button" class="btn btn-danger flex-fill" onclick="confirmCancel()">
                        <i class="material-icons-outlined me-2">cancel</i>
                        Yes, Cancel Order
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let currentSelectedStatus = '{{ $order->status }}';
        let currentOrderId = null;

        function openStatusModal() {
            document.getElementById('statusModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function selectStatus(status) {
            currentSelectedStatus = status;
            document.getElementById('selectedStatus').value = status;

            // Update UI
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('selected');
                if (option.dataset.status === status) {
                    option.classList.add('selected');
                }
            });
        }

        function openCancelModal(orderId, orderNumber) {
            currentOrderId = orderId;
            document.getElementById('cancelOrderNumber').textContent = orderNumber;
            document.getElementById('cancelModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            currentOrderId = null;
            document.getElementById('cancelReason').value = '';
        }

        function confirmCancel() {
            if (!currentOrderId) return;

            const reason = document.getElementById('cancelReason').value;
            const submitBtn = document.querySelector('#cancelModal .btn-danger');
            const originalText = submitBtn.innerHTML;

            // Show loading
            submitBtn.innerHTML = '<i class="material-icons-outlined me-2 spin-icon">refresh</i>Cancelling...';
            submitBtn.disabled = true;

            fetch('{{ route("wholesaler.orders.updateStatus", ":id") }}'.replace(':id', currentOrderId), {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: 'cancelled',
                    notes: reason || 'Order cancelled by wholesaler'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Order cancelled successfully');
                        closeCancelModal();

                        // Redirect to cancelled orders page
                        setTimeout(() => {
                            window.location.href = '{{ route("wholesaler.orders.cancelled") }}';
                        }, 1500);
                    } else {
                        showNotification('error', data.message || 'Failed to cancel order');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error cancelling order');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading
            submitBtn.innerHTML = '<i class="material-icons-outlined me-2 spin-icon">refresh</i>Updating...';
            submitBtn.disabled = true;

            // Add spin animation
            const spinIcon = submitBtn.querySelector('.material-icons-outlined');
            if (spinIcon) {
                spinIcon.style.animation = 'spin 1s linear infinite';
            }

            fetch('{{ route("wholesaler.orders.updateStatus", $order->id) }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: document.getElementById('selectedStatus').value,
                    notes: document.getElementById('statusNotes').value
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error updating status');
                })
                .finally(() => {
                    submitBtn.innerHTML = '<i class="material-icons-outlined me-2">save</i>Update Status';
                    submitBtn.disabled = false;
                });
        });

        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                min-width: 300px;
            `;

            const icon = type === 'success' ? 'check_circle' : 'error';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="material-icons-outlined me-2">${icon}</i>
                    <div>
                        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
                        <div>${message}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) notification.remove();
            }, 5000);
        }

        // Add spin animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .spin-icon {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);

        // Close modal on outside click
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) closeStatusModal();
        });

        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });
    </script>
@endpush
