@extends('wholesaler.layouts.app')
@push('title')
    Pending Orders
@endpush

@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --warning: #f59e0b;
        }

        .page-header {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
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
            border-left: 4px solid var(--warning);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-info h4 {
            margin-bottom: 5px;
            color: #1f2937;
        }

        .order-date {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .order-status {
            background: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .order-items {
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .item-price {
            color: var(--warning);
            font-weight: 600;
        }

        .order-summary {
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--warning);
        }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-confirm {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .customer-info {
            background: #f0f9ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .customer-info h6 {
            margin-bottom: 15px;
            color: #0369a1;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item i {
            width: 20px;
            margin-right: 10px;
            color: #0369a1;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold mb-2">Pending Orders</h1>
                <p class="mb-0">Orders awaiting your confirmation</p>
            </div>
            <div>
                <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-light">
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
                        <div class="order-header">
                            <div class="order-info">
                                <h4>Order #{{ $order->order_number }}</h4>
                                <div class="order-date">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    {{ $order->created_at->format('M d, Y - h:i A') }}
                                </div>
                            </div>
                            <div class="order-status">
                                <i class="fas fa-clock me-2"></i>
                                {{ ucfirst($order->status) }}
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="customer-info">
                            <h6><i class="fas fa-user me-2"></i>Customer Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $order->customer_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $order->customer_email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $order->shipping_address }}</span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="order-items">
                            <h6 class="mb-3"><i class="fas fa-boxes me-2"></i>Order Items</h6>
                            @foreach($order->orderItems as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->product_name }}</div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted">Qty: {{ $item->quantity }}</span>
                                                <span class="mx-2">•</span>
                                                <span class="text-muted">Unit Price: Rs. {{ number_format($item->unit_price) }}</span>
                                            </div>
                                            <div class="item-price">
                                                Rs. {{ number_format($item->total_price) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="order-summary">
                            <h6 class="mb-3"><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($order->subtotal) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>Rs. {{ number_format($order->shipping) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax:</span>
                                <span>Rs. {{ number_format($order->tax) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Total Amount:</span>
                                <span class="fw-bold">Rs. {{ number_format($order->total_amount) }}</span>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="order-summary mt-3">
                            <h6 class="mb-3"><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                            <div class="summary-row">
                                <span>Payment Method:</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Payment Status:</span>
                                <span class="{{ $order->payment_status == 'paid' ? 'text-success' : 'text-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        @if($order->notes)
                            <div class="order-summary mt-3">
                                <h6 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Order Notes</h6>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="order-actions">
                            <button class="btn-confirm" onclick="updateOrderStatus({{ $order->id }}, 'confirmed')">
                                <i class="fas fa-check-circle me-2"></i>
                                Confirm Order
                            </button>
                            <button class="btn-cancel" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                <i class="fas fa-times-circle me-2"></i>
                                Cancel Order
                            </button>
                            <a href="{{ route('wholesaler.orders.show', $order->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>
                                View Details
                            </a>
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
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4 class="fw-bold mb-3">No Pending Orders</h4>
            <p class="text-muted mb-4">All orders have been processed</p>
            <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>
                View All Orders
            </a>
        </div>
    @endif
@endsection

