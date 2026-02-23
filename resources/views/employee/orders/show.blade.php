@extends('employee.layout.app')
@push('title')
    Wholesaler Order #{{ $order->order_number }} | Details
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
        .shipping-details p,
        .wholesaler-details p {
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
                        <h1>Wholesaler Order #{{ $order->order_number }}</h1>
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
                            <a href="{{ route('employee.wholesaler.orders') }}"
                               class="btn-order-action">
                                <i class="material-icons-outlined me-2">arrow_back</i>
                                Back to Orders
                            </a>
                            <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                               class="btn-order-action">
                                <i class="material-icons-outlined me-2">download</i>
                                Download Invoice
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
                <!-- Wholesaler Details -->
                <div class="order-card">
                    <h5 class="card-title">
                        <i class="material-icons-outlined me-2">store</i>
                        Wholesaler Details
                    </h5>
                    <div class="wholesaler-details">
                        <p>
                            <span class="detail-label">Shop Name:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">store</i>
                                {{ $order->wholesaler->shop_name ?? 'N/A' }}
                            </span>
                        </p>
                        <p>
                            <span class="detail-label">Owner:</span>
                            <span class="detail-value">{{ $order->wholesaler->name ?? 'N/A' }}</span>
                        </p>
                        <p>
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">email</i>
                                {{ $order->wholesaler->email ?? 'N/A' }}
                            </span>
                        </p>
                        <p>
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value">
                                <i class="material-icons-outlined me-1" style="font-size: 16px;">phone</i>
                                {{ $order->wholesaler->phone ?? 'N/A' }}
                            </span>
                        </p>
                    </div>
                </div>

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
                            <th>Wholesaler Price</th>
                            <th>Employee Price</th>
                            <th>Profit Margin</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            @php
                                $product = $item->product;
                                $wholesalerPrice = $product->wholesaler_price ?? $item->unit_price;
                                $employeePrice = $product->employee_price ?? $wholesalerPrice;
                                $profit = $employeePrice - $wholesalerPrice;
                                $profitMargin = $wholesalerPrice > 0 ? ($profit / $wholesalerPrice) * 100 : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            @if($product && $product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $item->product_name }}"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <i class="material-icons-outlined text-muted">inventory_2</i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="product-name">{{ $item->product_name }}</div>
                                            <small class="text-muted">SKU: {{ $product->code ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="price">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                <td class="quantity">{{ $item->quantity }}</td>
                                <td class="price">Rs. {{ number_format($item->total_price, 2) }}</td>
                                <td class="price">Rs. {{ number_format($wholesalerPrice, 2) }}</td>
                                <td class="price">Rs. {{ number_format($employeePrice, 2) }}</td>
                                <td class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    <strong>
                                        {{ $profit >= 0 ? '+' : '-' }} Rs. {{ number_format(abs($profit), 2) }}
                                        <br>
                                        <small>({{ number_format($profitMargin, 2) }}%)</small>
                                    </strong>
                                </td>
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

                    <!-- Order Activity Log -->
                    @if($order->activities && $order->activities->count() > 0)
                        <div class="order-card">
                            <h5 class="card-title">
                                <i class="material-icons-outlined me-2">history</i>
                                Activity Log
                            </h5>
                            <div class="activity-log">
                                @foreach($order->activities->sortByDesc('created_at')->take(5) as $activity)
                                    <div class="activity-item mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $activity->description }}</strong>
                                            <small class="text-muted">{{ $activity->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                        @if($activity->causer)
                                            <small>By: {{ $activity->causer->name ?? 'System' }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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

                        <!-- Profit Summary (Employee View Only) -->
                        @php
                            $totalWholesalerCost = 0;
                            $totalEmployeePrice = 0;
                            foreach($order->items as $item) {
                                $product = $item->product;
                                $wholesalerPrice = $product->wholesaler_price ?? $item->unit_price;
                                $employeePrice = $product->employee_price ?? $wholesalerPrice;
                                $totalWholesalerCost += $wholesalerPrice * $item->quantity;
                                $totalEmployeePrice += $employeePrice * $item->quantity;
                            }
                            $totalProfit = $totalEmployeePrice - $totalWholesalerCost;
                        @endphp

                        <div class="mt-4 pt-4 border-top">
                            <div class="summary-row">
                                <span class="summary-label">Wholesaler Cost</span>
                                <span class="summary-value">Rs. {{ number_format($totalWholesalerCost, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Employee Price</span>
                                <span class="summary-value">Rs. {{ number_format($totalEmployeePrice, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Gross Profit</span>
                                <span class="summary-value {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($totalProfit, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Add any JavaScript functionality needed for the employee view
        document.addEventListener('DOMContentLoaded', function() {
            // You can add any interactive features here
            console.log('Wholesaler Order Details page loaded for employee');
        });
    </script>
@endpush
