@extends('wholesaler.app')
@push('title')
    Order Details - #{{ $order->id }}
@endpush
@push('css')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-title h4 {
            color: white;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 2px solid #e2e8f0;
            padding: 20px 25px;
        }

        .card-header h5 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 25px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .info-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
        }

        .table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #475569;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f1f5f9;
        }

        .badge {
            padding: 8px 16px;
            font-weight: 600;
            font-size: 12px;
            border-radius: 50px;
        }

        .bg-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
        .bg-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
        .bg-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; }
        .bg-info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; }

        .order-total {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .total-row:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 18px;
            color: #1e293b;
        }

        .status-badge {
            font-size: 14px;
            padding: 10px 20px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="fw-bold">Order Details</h4>
                <h6>Order #{{ $order->id }} - {{ $order->created_at->format('M d, Y') }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('wholesaler.orders') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="order-card">
                <div class="card-header">
                    <h5><i class="fas fa-shopping-cart me-2"></i>Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image ?? false)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     alt="{{ $item->product->name }}" class="product-img me-3">
                                            @else
                                                <div class="product-img bg-light d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name ?? 'Product Not Available' }}</strong>
                                                @if($item->product->sku ?? false)
                                                    <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>RS. {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>RS. {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Total -->
                    <div class="order-total">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>RS. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>RS. {{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Tax:</span>
                            <span>RS. {{ number_format($order->tax_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Grand Total:</span>
                            <span>RS. {{ number_format($order->total_amount + ($order->shipping_cost ?? 0) + ($order->tax_amount ?? 0), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Information -->
            <div class="order-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="order-info-grid">
                        <div class="info-item">
                            <div class="info-label">Order Status</div>
                            <div class="info-value">
                                <span class="badge status-badge
                                    @if($order->status == 'confirmed') bg-success
                                    @elseif($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'processing') bg-info
                                    @elseif($order->status == 'completed') bg-success
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Order Date</div>
                            <div class="info-value">
                                {{ $order->created_at->format('M d, Y') }}<br>
                                <small>{{ $order->created_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Method</div>
                            <div class="info-value">{{ $order->payment_method ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($order->payment_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <form action="{{ route('wholesaler.orders.status.update', $order->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Order Status</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="order-card">
                <div class="card-header">
                    <h5><i class="fas fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <div class="info-label">Customer Name</div>
                        <div class="info-value">{{ $order->customer->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item mb-3">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->customer->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item mb-3">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $order->customer->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Shipping Address</div>
                        <div class="info-value">{{ $order->shipping_address ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
