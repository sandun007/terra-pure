@extends('seller.layout.app')

@push('title')
    Print Invoice
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 20px;
            }
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        .action-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 8px;
            letter-spacing: 1px;
        }

        .invoice-subtitle {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-complete {
            background: #d1fae5;
            color: #065f46;
        }

        .status-return {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-rescheduling {
            background: #dbeafe;
            color: #1e40af;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .detail-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .detail-box h3 {
            margin: 0 0 16px 0;
            font-size: 16px;
            color: #0f172a;
            font-weight: 700;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
        }

        .detail-value {
            font-weight: 600;
            color: #0f172a;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 32px 0;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-table th {
            background: #f1f5f9;
            padding: 14px 12px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 2px solid #cbd5e1;
        }

        .product-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #334155;
        }

        .product-table tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 30px;
            margin-bottom: 10px;
        }

        .total-label {
            font-weight: 600;
            color: #64748b;
            width: 120px;
            text-align: right;
        }

        .total-value {
            font-weight: 700;
            width: 150px;
            text-align: right;
            color: #0f172a;
        }

        .grand-total {
            font-size: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .grand-total .total-label,
        .grand-total .total-value {
            color: #ef4444;
            font-weight: 800;
        }

        .notes-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .notes-section strong {
            display: block;
            margin-bottom: 10px;
            color: #0f172a;
            font-size: 15px;
        }

        .notes-section p {
            margin: 0;
            color: #475569;
            line-height: 1.6;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            padding-top: 20px;
            border-top: 1px dashed #e2e8f0;
        }

        .footer p {
            margin: 5px 0;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .company-details {
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .invoice-container {
                padding: 20px;
            }

            .invoice-details {
                grid-template-columns: 1fr;
            }

            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .total-row {
                flex-direction: column;
                align-items: flex-end;
                gap: 5px;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Debug information (remove in production) --}}
    {{-- @php
        dd(isset($order) ? 'Order exists' : 'Order does not exist');
    @endphp --}}

    @if(!isset($order) || !$order)
        <div class="invoice-wrapper">
            <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 20px; border-radius: 12px; text-align: center;">
                <span class="material-icons-round" style="font-size: 48px; margin-bottom: 10px;">error</span>
                <h3>Order Not Found</h3>
                <p>The order you're trying to view does not exist or you don't have permission to access it.</p>
                <a href="{{ route('seller.pending-order') }}" class="btn btn-primary" style="margin-top: 20px;">
                    <span class="material-icons-round">arrow_back</span>
                    Back to Orders
                </a>
            </div>
        </div>
    @else
        <div class="invoice-wrapper">
            <!-- Action Buttons - Hidden when printing -->
            <div class="action-bar no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    <span class="material-icons-round" style="font-size:18px;">print</span>
                    Print Invoice
                </button>
                <a href="{{ route('seller.order.show', $order->id) }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px;">arrow_back</span>
                    Back to Order
                </a>
                <a href="{{ route('seller.pending-order') }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px;">list</span>
                    All Orders
                </a>
            </div>

            <!-- Invoice Content -->
            <div class="invoice-container">
                <!-- Company Header -->
                <div class="company-info">
                    <div class="company-name">{{ config('app.name', 'Your Store') }}</div>
                    <div class="company-details">
                        <div>123 Business Street, Colombo, Sri Lanka</div>
                        <div>Tel: +94 11 234 5678 | Email: info@yourstore.com</div>
                    </div>
                </div>

                <!-- Invoice Header -->
                <div class="invoice-header">
                    <h1 class="invoice-title">INVOICE</h1>
                    <div class="invoice-subtitle">#{{ $order->order_id ?? $order->order_number ?? 'N/A' }}</div>
                    <div>
                        <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                    </div>
                </div>

                <!-- Order and Customer Details -->
                <div class="invoice-details">
                    <div class="detail-box">
                        <h3>Order Information</h3>
                        <div class="detail-row">
                            <span class="detail-label">Order Date:</span>
                            <span class="detail-value">
                                @if($order->order_date && method_exists($order->order_date, 'format'))
                                    {{ $order->order_date->format('d M Y') }}
                                @else
                                    {{ date('d M Y', strtotime($order->order_date ?? now())) }}
                                @endif
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Platform:</span>
                            <span class="detail-value">{{ $order->platform ?? 'Website' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method:</span>
                            <span class="detail-value">{{ ucfirst($order->payment_method ?? 'Not Specified') }}</span>
                        </div>
                        @if($order->created_at)
                            <div class="detail-row">
                                <span class="detail-label">Created:</span>
                                <span class="detail-value">
                                @if(method_exists($order->created_at, 'format'))
                                        {{ $order->created_at->format('d M Y h:i A') }}
                                    @else
                                        {{ date('d M Y h:i A', strtotime($order->created_at)) }}
                                    @endif
                            </span>
                            </div>
                        @endif
                    </div>

                    <div class="detail-box">
                        <h3>Customer Information</h3>
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">{{ $order->customer_name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value">{{ $order->customer_phone ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($order->customer_email))
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">{{ $order->customer_email }}</span>
                            </div>
                        @endif
                        @if(!empty($order->customer_address))
                            <div class="detail-row">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value">{{ $order->customer_address }}</span>
                            </div>
                        @endif
                        @if(!empty($order->district))
                            <div class="detail-row">
                                <span class="detail-label">District:</span>
                                <span class="detail-value">{{ $order->district }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Products Table -->
                <table class="product-table">
                    <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price (Rs.)</th>
                        <th>Total (Rs.)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($order->items ?? [] as $item)
                        <tr>
                            <td>{{ $item->product_code ?? 'N/A' }}</td>
                            <td>{{ $item->product_name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity ?? 0 }}</td>
                            <td>Rs. {{ number_format($item->unit_price ?? 0, 2) }}</td>
                            <td>Rs. {{ number_format($item->total_price ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        @if($order->product_name)
                            <tr>
                                <td>{{ $order->product_code ?? 'N/A' }}</td>
                                <td>{{ $order->product_name }}</td>
                                <td>{{ $order->quantity ?? 1 }}</td>
                                <td>Rs. {{ number_format($order->product_price ?? 0, 2) }}</td>
                                <td>Rs. {{ number_format($order->total_amount ?? 0, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">
                                    No product details available
                                </td>
                            </tr>
                        @endif
                    @endforelse
                    </tbody>
                </table>

                <!-- Total Calculation -->
                <div class="total-section">
                    @php
                        $subtotal = $order->subtotal ?? ($order->product_price * $order->quantity);
                        $discountPercent = $order->discount ?? 0;
                        $discountAmount = ($subtotal * $discountPercent) / 100;
                        $total = $order->final_total ?? ($subtotal - $discountAmount);
                    @endphp

                    <div class="total-row">
                        <span class="total-label">Subtotal:</span>
                        <span class="total-value">Rs. {{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discountPercent > 0)
                        <div class="total-row">
                            <span class="total-label">Discount ({{ $discountPercent }}%):</span>
                            <span class="total-value" style="color: #10b981;">- Rs. {{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif

                    <div class="total-row grand-total">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-value">Rs. {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Order Notes -->
                @if(!empty($order->remark))
                    <div class="notes-section">
                        <strong>Order Notes:</strong>
                        <p>{{ $order->remark }}</p>
                    </div>
                @endif

                <!-- Delivery Information -->
                @if($order->delivery_date || $order->rescheduling_date || $order->return_date)
                    <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px;">
                        <h4 style="margin: 0 0 10px; font-size: 14px; color: #0f172a;">Delivery Information</h4>
                        @if($order->delivery_date)
                            <div class="detail-row">
                                <span class="detail-label">Delivery Date:</span>
                                <span class="detail-value">
                            @if(method_exists($order->delivery_date, 'format'))
                                        {{ $order->delivery_date->format('d M Y') }}
                                    @else
                                        {{ date('d M Y', strtotime($order->delivery_date)) }}
                                    @endif
                        </span>
                            </div>
                        @endif
                        @if($order->rescheduling_date)
                            <div class="detail-row">
                                <span class="detail-label">Rescheduled Date:</span>
                                <span class="detail-value">
                            @if(method_exists($order->rescheduling_date, 'format'))
                                        {{ $order->rescheduling_date->format('d M Y') }}
                                    @else
                                        {{ date('d M Y', strtotime($order->rescheduling_date)) }}
                                    @endif
                        </span>
                            </div>
                        @endif
                        @if($order->return_date)
                            <div class="detail-row">
                                <span class="detail-label">Return Date:</span>
                                <span class="detail-value">
                            @if(method_exists($order->return_date, 'format'))
                                        {{ $order->return_date->format('d M Y') }}
                                    @else
                                        {{ date('d M Y', strtotime($order->return_date)) }}
                                    @endif
                        </span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Footer -->
                <div class="footer">
                    <p>Thank you for your business!</p>
                    <p>This is a computer generated invoice - no signature required.</p>
                    <p style="margin-top: 10px;">Invoice #{{ $order->order_id ?? $order->order_number ?? '' }} | Generated on {{ now()->format('d M Y h:i A') }}</p>
                </div>
            </div>

            <!-- Bottom Action Buttons - Hidden when printing -->
            <div class="action-bar no-print" style="margin-top: 24px; justify-content: center;">
                <button onclick="window.print()" class="btn btn-primary">
                    <span class="material-icons-round" style="font-size:18px;">print</span>
                    Print Invoice
                </button>
            </div>
        </div>
    @endif
@endsection
