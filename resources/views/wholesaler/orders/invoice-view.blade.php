@extends('wholesaler.layouts.app')
@push('title')
    Invoice #{{ $order->order_number }}
@endpush

@push('css')
    <style>
        .invoice-view {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .invoice-paper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #f1f5f9;
        }

        .company-logo {
            max-height: 60px;
            margin-bottom: 15px;
        }

        .company-info h1 {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .invoice-title h2 {
            font-size: 36px;
            color: #6366f1;
            margin: 0;
            font-weight: 800;
        }

        .invoice-meta {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .invoice-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
            padding: 30px;
            background: #f8fafc;
            border-radius: 10px;
        }

        .detail-section h4 {
            font-size: 16px;
            color: #475569;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-item {
            margin-bottom: 8px;
            display: flex;
        }

        .detail-label {
            font-weight: 600;
            color: #475569;
            min-width: 120px;
        }

        .items-section {
            margin: 40px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .items-table thead {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .items-table th {
            padding: 18px 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.3s ease;
        }

        .items-table tbody tr:hover {
            background: #f8fafc;
        }

        .items-table td {
            padding: 16px 20px;
            font-size: 14px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 30px;
            border-radius: 10px;
            margin: 40px 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .summary-total {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            border-bottom: none;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 2px solid #cbd5e1;
        }

        .invoice-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-print {
            background: #6366f1;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        }

        .footer-note {
            margin-top: 30px;
            padding: 20px;
            background: #fef3c7;
            border-radius: 8px;
            color: #92400e;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0, 0, 0, 0.03);
            font-weight: 800;
            pointer-events: none;
            z-index: 1;
        }

        @media print {
            .invoice-actions,
            .watermark {
                display: none !important;
            }

            .invoice-paper {
                box-shadow: none;
                padding: 0;
            }

            body {
                background: white !important;
            }
        }

        @media (max-width: 768px) {
            .invoice-header {
                flex-direction: column;
                gap: 20px;
            }

            .invoice-details-grid {
                grid-template-columns: 1fr;
            }

            .invoice-paper {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="invoice-view">
        <div class="invoice-paper">
            <!-- Watermark -->
            <div class="watermark">INVOICE</div>

            <!-- Header -->
            <div class="invoice-header">
                <div class="company-info">
                    <h1>{{ auth()->guard('wholesaler')->user()->business_name ?? 'Wholesale Business' }}</h1>
                    <div class="contact-info">
                        <p>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ auth()->guard('wholesaler')->user()->address ?? '123 Business Street, City' }}
                        </p>
                        <p>
                            <i class="fas fa-phone me-2"></i>
                            {{ auth()->guard('wholesaler')->user()->phone ?? '+123 456 7890' }}
                        </p>
                        <p>
                            <i class="fas fa-envelope me-2"></i>
                            {{ auth()->guard('wholesaler')->user()->email ?? 'info@business.com' }}
                        </p>
                    </div>
                </div>
                <div class="invoice-title">
                    <h2>INVOICE</h2>
                    <div class="invoice-meta">
                        <div><strong>Invoice #:</strong> {{ $order->order_number }}</div>
                        <div><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</div>
                        <div><strong>Status:</strong>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="invoice-details-grid">
                <div class="detail-section">
                    <h4>Bill To</h4>
                    <div class="detail-item">
                        <span class="detail-label">Name:</span>
                        <span>{{ $order->customer_name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span>{{ $order->customer_email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone:</span>
                        <span>{{ $order->customer_phone }}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h4>Shipping Details</h4>
                    <div class="detail-item">
                        <span class="detail-label">Address:</span>
                        <span>{{ $order->shipping_address }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Method:</span>
                        <span>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Payment Status:</span>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-section">
                <h4 class="mb-4">Order Items</h4>
                <table class="items-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->product_name }}</div>
                                @if($item->product)
                                    <small class="text-muted">SKU: {{ $item->product->code }}</small>
                                @endif
                            </td>
                            <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right fw-bold">Rs. {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span>Tax</span>
                    <span>Rs. {{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span>Shipping</span>
                    <span>Rs. {{ number_format($order->shipping, 2) }}</span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total Amount</span>
                    <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            @if($order->notes)
                <div class="footer-note">
                    <h5><i class="fas fa-sticky-note me-2"></i>Order Notes</h5>
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="invoice-actions">
                <button onclick="window.print()" class="btn-print">
                    <i class="fas fa-print me-2"></i>
                    Print Invoice
                </button>
                <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}" class="btn-print">
                    <i class="fas fa-download me-2"></i>
                    Download PDF
                </a>
                <a href="{{ route('wholesaler.orders.show', $order->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Order
                </a>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Auto print option
        @if(request('print'))
            window.onload = function() {
            window.print();
        }
        @endif
    </script>
@endpush
