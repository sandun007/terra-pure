@extends('wholesaler.layouts.app')
@push('title')
    Order #{{ $order->order_number }}
@endpush

@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        .order-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .order-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 2rem 0;
        }

        .order-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .order-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .order-status-badge {
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-processing { background: #e0e7ff; color: #3730a3; }
        .status-shipped { background: #f0f9ff; color: #075985; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .order-timeline {
            position: relative;
            padding: 2rem;
        }

        .timeline-track {
            position: relative;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 3rem 0;
        }

        .timeline-progress {
            position: absolute;
            height: 100%;
            background: var(--primary);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .timeline-points {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .timeline-point {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 120px;
        }

        .timeline-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e5e7eb;
            margin: 0 auto 10px;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .timeline-point.active .timeline-dot {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        .timeline-point.completed .timeline-dot {
            background: var(--success);
            border-color: var(--success);
        }

        .timeline-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .timeline-point.active .timeline-label,
        .timeline-point.completed .timeline-label {
            color: var(--primary);
        }

        .timeline-date {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .info-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .info-card h6 {
            color: #374151;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-action {
            background: var(--primary);
            color: white;
        }

        .btn-primary-action:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-success-action {
            background: var(--success);
            color: white;
        }

        .btn-success-action:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-outline-action {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline-action:hover {
            background: var(--primary);
            color: white;
        }

        .status-selector {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .timeline-point {
                width: 80px;
            }

            .timeline-label {
                font-size: 0.7rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="order-container">
        <div class="container">
            <div class="order-card">
                <!-- Order Header -->
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h3 fw-bold mb-2">Order #{{ $order->order_number }}</h1>
                            <p class="mb-0 opacity-90">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div class="text-end">
                            <div class="order-status-badge status-{{ $order->status }}">
                                <i class="fas fa-circle"></i>
                                {{ ucfirst($order->status) }}
                            </div>
                            <div class="mt-2">
                                <h3 class="fw-bold mb-0">Rs. {{ number_format($order->total_amount, 2) }}</h3>
                                <small class="opacity-90">Total Amount</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="order-timeline">
                    <div class="timeline-track">
                        <div class="timeline-progress" style="width:
                            @if($order->status == 'pending') 0%
                            @elseif($order->status == 'confirmed') 25%
                            @elseif($order->status == 'processing') 50%
                            @elseif($order->status == 'shipped') 75%
                            @elseif($order->status == 'completed') 100%
                            @else 0%
                            @endif">
                        </div>
                    </div>
                    <div class="timeline-points">
                        <div class="timeline-point @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'completed'])) completed @elseif($order->status == 'pending') active @endif" style="left: 0%;">
                            <div class="timeline-dot"></div>
                            <div class="timeline-label">Pending</div>
                            <div class="timeline-date">{{ $order->created_at->format('M d') }}</div>
                        </div>
                        <div class="timeline-point @if(in_array($order->status, ['processing', 'shipped', 'completed'])) completed @elseif($order->status == 'confirmed') active @endif" style="left: 25%;">
                            <div class="timeline-dot"></div>
                            <div class="timeline-label">Confirmed</div>
                            <div class="timeline-date">
                                @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'completed']))
                                    {{ $order->status_updated_at?->format('M d') ?? '--' }}
                                @endif
                            </div>
                        </div>
                        <div class="timeline-point @if(in_array($order->status, ['shipped', 'completed'])) completed @elseif($order->status == 'processing') active @endif" style="left: 50%;">
                            <div class="timeline-dot"></div>
                            <div class="timeline-label">Processing</div>
                            <div class="timeline-date">
                                @if(in_array($order->status, ['shipped', 'completed']))
                                    {{ $order->status_updated_at?->format('M d') ?? '--' }}
                                @endif
                            </div>
                        </div>
                        <div class="timeline-point @if($order->status == 'completed') completed @elseif($order->status == 'shipped') active @endif" style="left: 75%;">
                            <div class="timeline-dot"></div>
                            <div class="timeline-label">Shipped</div>
                            <div class="timeline-date">
                                @if($order->status == 'completed')
                                    {{ $order->status_updated_at?->format('M d') ?? '--' }}
                                @endif
                            </div>
                        </div>
                        <div class="timeline-point @if($order->status == 'completed') active @endif" style="left: 100%;">
                            <div class="timeline-dot"></div>
                            <div class="timeline-label">Completed</div>
                            <div class="timeline-date">
                                @if($order->status == 'completed')
                                    {{ $order->status_updated_at?->format('M d') ?? '--' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row p-4">
                    <!-- Left Column: Order Details -->
                    <div class="col-lg-8">
                        <!-- Customer Information -->
                        <div class="info-card">
                            <h6><i class="fas fa-user me-2"></i>Customer Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="text-muted">Name</span>
                                        <span class="fw-bold">{{ $order->customer_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="text-muted">Email</span>
                                        <span>{{ $order->customer_email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="text-muted">Phone</span>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <span class="text-muted">Shipping Address</span>
                                        <span class="text-end">{{ $order->shipping_address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="info-card">
                            <h6><i class="fas fa-boxes me-2"></i>Order Items ({{ $order->orderItems->count() }})</h6>
                            @foreach($order->orderItems as $item)
                                <div class="product-card">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="rounded"
                                                     width="60"
                                                     height="60">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                                            @if($item->product)
                                                <small class="text-muted">SKU: {{ $item->product->code }}</small>
                                            @endif
                                        </div>
                                        <div class="col-auto text-end">
                                            <div class="fw-bold text-primary">Rs. {{ number_format($item->total_price, 2) }}</div>
                                            <small class="text-muted">{{ $item->quantity }} × Rs. {{ number_format($item->unit_price, 2) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Notes -->
                        @if($order->notes)
                            <div class="info-card">
                                <h6><i class="fas fa-sticky-note me-2"></i>Order Notes</h6>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Order Summary & Actions -->
                    <div class="col-lg-4">
                        <!-- Order Summary -->
                        <div class="info-card">
                            <h6><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                            <div class="info-item">
                                <span>Subtotal</span>
                                <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="info-item">
                                <span>Shipping</span>
                                <span class="text-success">Rs. {{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <div class="info-item">
                                <span>Tax</span>
                                <span>Rs. {{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="info-item" style="border-top: 2px solid #e5e7eb; padding-top: 1rem;">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold text-primary">Rs. {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="info-card">
                            <h6><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                            <div class="info-item">
                                <span>Method</span>
                                <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="info-item">
                                <span>Status</span>
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            @if($order->transaction_id)
                                <div class="info-item">
                                    <span>Transaction ID</span>
                                    <span class="text-muted">{{ $order->transaction_id }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Status Update -->
                        @if(in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped']))
                            <div class="status-selector">
                                <h6 class="mb-3"><i class="fas fa-sync-alt me-2"></i>Update Status</h6>
                                <div class="mb-3">
                                    <select class="form-select" id="statusSelect">
                                        <option value="">Select new status</option>
                                        @if($order->status == 'pending')
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                        @elseif($order->status == 'confirmed')
                                            <option value="processing">Processing</option>
                                            <option value="cancelled">Cancelled</option>
                                        @elseif($order->status == 'processing')
                                            <option value="shipped">Shipped</option>
                                            <option value="cancelled">Cancelled</option>
                                        @elseif($order->status == 'shipped')
                                            <option value="completed">Completed</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" id="statusNotes" rows="3"
                                              placeholder="Add notes about this status change..."></textarea>
                                </div>
                                <button class="btn btn-primary w-100" onclick="updateOrderStatus()">
                                    <i class="fas fa-check me-2"></i>
                                    Update Status
                                </button>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                               class="btn-action btn-primary-action">
                                <i class="fas fa-download"></i>
                                Download Invoice
                            </a>
                            <a href="{{ route('wholesaler.orders.invoice.view', $order->id) }}"
                               target="_blank"
                               class="btn-action btn-outline-action">
                                <i class="fas fa-print"></i>
                                Print Invoice
                            </a>
                            <a href="{{ route('wholesaler.orders.index') }}"
                               class="btn-action btn-outline-action">
                                <i class="fas fa-arrow-left"></i>
                                Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Update order status
        function updateOrderStatus() {
            const status = document.getElementById('statusSelect').value;
            const notes = document.getElementById('statusNotes').value;

            if (!status) {
                showNotification('warning', 'Please select a status');
                return;
            }

            if (!confirm(`Are you sure you want to update order status to "${status}"?`)) {
                return;
            }

            $.ajax({
                url: `{{ route('wholesaler.orders.updateStatus', $order->id) }}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    notes: notes
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    showNotification('error', xhr.responseJSON?.message || 'An error occurred');
                }
            });
        }

        // Show notification
        function showNotification(type, message) {
            // Remove existing notifications
            document.querySelectorAll('.alert-notification').forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-notification alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
@endpush
