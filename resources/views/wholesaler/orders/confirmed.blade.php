@extends('wholesaler.layouts.app')
@push('title')
    Confirmed Orders
@endpush

@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --info: #3b82f6;
        }

        .page-header {
            background: linear-gradient(135deg, var(--info) 0%, #1d4ed8 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--info);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--info);
        }

        .timeline-item.completed::before {
            background: var(--info);
        }

        .timeline-content {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #dbeafe;
        }

        .timeline-date {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .timeline-status {
            font-weight: 600;
            color: #1e40af;
        }

        .processing-steps {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }

        .processing-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.25rem;
            color: #9ca3af;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            background: var(--info);
            border-color: var(--info);
            color: white;
        }

        .step.completed .step-icon {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #9ca3af;
        }

        .step.active .step-label {
            color: var(--info);
        }

        .shipping-info {
            background: #f0f9ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .btn-process {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-process:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-ship {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-ship:hover {
            background: #d97706;
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold mb-2">Confirmed Orders</h1>
                <p class="mb-0">Orders ready for processing and shipping</p>
            </div>
            <div>
                <a href="{{ route('wholesaler.wholesaler.orders.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to All Orders
                </a>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="order-card">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-2">Order #{{ $order->order_number }}</h4>
                                <div class="text-muted">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    Confirmed on {{ $order->updated_at->format('M d, Y - h:i A') }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-info text-white mb-2">Confirmed</div>
                                <div class="fw-bold text-primary">Rs. {{ number_format($order->total_amount) }}</div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3"><i class="fas fa-user me-2"></i>Customer Details</h6>
                                <div class="mb-2">
                                    <strong>{{ $order->customer_name }}</strong>
                                </div>
                                <div class="text-muted mb-1">
                                    <i class="fas fa-envelope me-2"></i>{{ $order->customer_email }}
                                </div>
                                <div class="text-muted">
                                    <i class="fas fa-phone me-2"></i>{{ $order->customer_phone }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h6>
                                <p class="mb-0">{{ $order->shipping_address }}</p>
                            </div>
                        </div>

                        <!-- Processing Steps -->
                        <div class="processing-steps">
                            <div class="step {{ $order->status === 'confirmed' ? 'active' : 'completed' }}">
                                <div class="step-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="step-label">Confirmed</div>
                            </div>
                            <div class="step {{ $order->status === 'processing' ? 'active' : '' }}">
                                <div class="step-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="step-label">Processing</div>
                            </div>
                            <div class="step">
                                <div class="step-icon">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="step-label">Shipping</div>
                            </div>
                            <div class="step">
                                <div class="step-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div class="step-label">Completed</div>
                            </div>
                        </div>

                        <!-- Order Items Summary -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-boxes me-2"></i>Order Items
                                ({{ $order->orderItems->count() }})</h6>
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                @foreach($order->orderItems as $item)
                                    <div class="col">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="rounded me-3"
                                                     width="50"
                                                     height="50">
                                            @else
                                                <div
                                                    class="rounded bg-white d-flex align-items-center justify-content-center me-3"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="fw-bold mb-1">{{ $item->product_name }}</div>
                                                <div class="text-muted small">
                                                    Qty: {{ $item->quantity }} ×
                                                    Rs. {{ number_format($item->unit_price) }}
                                                </div>
                                            </div>
                                            <div class="fw-bold text-primary">
                                                Rs. {{ number_format($item->total_price) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                            <div>
                                <span class="text-muted me-3">Payment Status:</span>
                                <span
                                    class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="d-flex gap-3">
                                <button class="btn-process" onclick="updateOrderStatus({{ $order->id }}, 'processing')">
                                    <i class="fas fa-cog me-2"></i>
                                    Start Processing
                                </button>
                                <a href="{{ route('wholesaler.wholesaler.orders.show', $order->id) }}"
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-check-circle fa-4x text-muted"></i>
            </div>
            <h4 class="fw-bold mb-3">No Confirmed Orders</h4>
            <p class="text-muted mb-4">All confirmed orders have been processed</p>
            <a href="{{ route('wholesaler.wholesaler.orders.pending') }}" class="btn btn-primary">
                <i class="fas fa-clock me-2"></i>
                View Pending Orders
            </a>
        </div>
    @endif
@endsection

@push('script')
    <script>
        function updateOrderStatus(orderId, status) {
            if (!confirm(`Start processing this order?`)) {
                return;
            }

            $.ajax({
                url: `{{ route('wholesaler.wholesaler.orders.updateStatus', '') }}/${orderId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    notes: 'Order processing started'
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('success', 'Order moved to processing!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    showNotification('error', xhr.responseJSON?.message || 'An error occurred');
                }
            });
        }

        function showNotification(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
@endpush
