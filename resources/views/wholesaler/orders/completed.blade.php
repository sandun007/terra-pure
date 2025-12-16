@extends('wholesaler.layouts.app')
@push('title')
    Completed Orders
@endpush

@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --success: #10b981;
        }

        .page-header {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
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
            border-left: 4px solid var(--success);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .completion-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .rating-section {
            background: #f0f9ff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 1.5rem;
        }

        .revenue-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--success) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .revenue-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .revenue-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .revenue-label {
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-invoice {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-invoice:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-reorder {
            background: #f0f9ff;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reorder:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .customer-feedback {
            background: #fef3c7;
            border: 1px dashed #f59e0b;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .timeline-completed {
            position: relative;
            padding-left: 30px;
            margin: 20px 0;
        }

        .timeline-completed::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #10b981;
        }

        .timeline-item-completed {
            position: relative;
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .timeline-item-completed::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid white;
            box-shadow: 0 0 0 3px #10b981;
        }

        .timeline-date-completed {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .timeline-status-completed {
            font-weight: 600;
            color: #065f46;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold mb-2">Completed Orders</h1>
                <p class="mb-0">Successfully delivered orders</p>
            </div>
            <div>
                <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to All Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="revenue-card">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="revenue-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="revenue-value">Rs. {{ number_format($orders->sum('total_amount')) }}</div>
                <div class="revenue-label">Total Revenue from {{ $orders->count() }} Completed Orders</div>
            </div>
            <div class="text-end">
                <div class="fs-1 fw-bold">{{ $orders->count() }}</div>
                <div class="revenue-label">Completed Orders</div>
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
                                <div class="completion-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Completed on {{ $order->updated_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success fs-3 mb-2">Rs. {{ number_format($order->total_amount) }}</div>
                                <div class="text-muted">Customer: {{ $order->customer_name }}</div>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="timeline-completed">
                            <div class="timeline-item-completed">
                                <div class="timeline-date-completed">
                                    {{ $order->created_at->format('M d, Y - h:i A') }}
                                </div>
                                <div class="timeline-status-completed">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Order Placed
                                </div>
                            </div>

                            <div class="timeline-item-completed">
                                <div class="timeline-date-completed">
                                    {{ $order->created_at->addHours(2)->format('M d, Y - h:i A') }}
                                </div>
                                <div class="timeline-status-completed">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Order Confirmed
                                </div>
                            </div>

                            <div class="timeline-item-completed">
                                <div class="timeline-date-completed">
                                    {{ $order->created_at->addHours(4)->format('M d, Y - h:i A') }}
                                </div>
                                <div class="timeline-status-completed">
                                    <i class="fas fa-cog me-2"></i>
                                    Order Processed
                                </div>
                            </div>

                            <div class="timeline-item-completed">
                                <div class="timeline-date-completed">
                                    {{ $order->created_at->addDays(1)->format('M d, Y - h:i A') }}
                                </div>
                                <div class="timeline-status-completed">
                                    <i class="fas fa-shipping-fast me-2"></i>
                                    Order Shipped
                                </div>
                            </div>

                            <div class="timeline-item-completed">
                                <div class="timeline-date-completed">
                                    {{ $order->updated_at->format('M d, Y - h:i A') }}
                                </div>
                                <div class="timeline-status-completed">
                                    <i class="fas fa-check-double me-2"></i>
                                    Order Delivered & Completed
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-boxes me-2"></i>Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="rounded me-2"
                                                             width="40"
                                                             height="40">
                                                    @endif
                                                    <span>{{ $item->product_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rs. {{ number_format($item->unit_price) }}</td>
                                            <td class="fw-bold">Rs. {{ number_format($item->total_price) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="bg-light rounded p-3 mb-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Order Summary</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>Rs. {{ number_format($order->subtotal) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span>Rs. {{ number_format($order->shipping) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax:</span>
                                        <span>Rs. {{ number_format($order->tax) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold pt-2 border-top">
                                        <span>Total:</span>
                                        <span class="text-success">Rs. {{ number_format($order->total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-credit-card me-2"></i>Payment Details</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Method:</span>
                                        <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Status:</span>
                                        <span class="badge bg-success">Paid</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Transaction ID:</span>
                                        <span class="text-muted">{{ $order->order_number }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-light rounded p-3 mt-3">
                            <h6 class="fw-bold mb-3"><i class="fas fa-user me-2"></i>Customer Information</h6>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="text-muted small">Name</div>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="text-muted small">Email</div>
                                    <div>{{ $order->customer_email }}</div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="text-muted small">Phone</div>
                                    <div>{{ $order->customer_phone }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="text-muted small">Shipping Address</div>
                                    <div>{{ $order->shipping_address }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mt-4 pt-4 border-top">
                            <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                               class="btn-invoice">
                                <i class="fas fa-download me-2"></i>
                                Download Invoice
                            </a>
                            <a href="{{ route('wholesaler.orders.show', $order->id) }}"
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>
                                View Details
                            </a>
                            <a href="{{ route('wholesaler.orders.invoice.view', $order->id) }}"
                               target="_blank"
                               class="btn-reorder">
                                <i class="fas fa-print me-2"></i>
                                Print Invoice
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
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-check-double fa-4x text-muted"></i>
            </div>
            <h4 class="fw-bold mb-3">No Completed Orders</h4>
            <p class="text-muted mb-4">Completed orders will appear here</p>
            <a href="{{ route('wholesaler.orders.confirmed') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>
                View Processing Orders
            </a>
        </div>
    @endif
@endsection

@push('script')
    <script>
        function printInvoice(orderId) {
            window.open(`{{ url('wholesaler/orders') }}/${orderId}/invoice/view`, '_blank');
        }
    </script>
@endpush
