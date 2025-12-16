<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8fafc;
            padding: 40px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .invoice-logo {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .invoice-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .invoice-body {
            padding: 40px;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-section {
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .info-item {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 40px;
        }

        .items-table th {
            background: #f1f5f9;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .product-name {
            font-weight: 600;
            color: #1e293b;
        }

        .product-sku {
            font-size: 0.875rem;
            color: #64748b;
        }

        .total-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #bae6fd;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .total-row:last-child {
            border-bottom: none;
            font-size: 1.5rem;
            font-weight: 700;
            color: #6366f1;
        }

        .invoice-footer {
            padding: 30px 40px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .payment-info {
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 20px;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-weight: 600;
        }

        .note-box {
            background: #fef3c7;
            border: 1px dashed #f59e0b;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #6366f1;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .print-button:hover {
            background: #4f46e5;
            transform: translateY(-2px);
        }

        .download-button {
            position: fixed;
            bottom: 30px;
            right: 180px;
            background: #10b981;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .download-button:hover {
            background: #059669;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <div class="invoice-logo">{{ $shop->shop_name ?? 'WHOLESALE STORE' }}</div>
        <div class="invoice-title">TAX INVOICE</div>
        <div class="d-flex justify-content-center gap-4 mt-3">
            <div class="status-badge">
                <i class="fas fa-check-circle"></i>
                {{ ucfirst($order->status) }}
            </div>
            <div class="payment-method">
                <i class="fas fa-credit-card"></i>
                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="invoice-body">
        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="info-section">
                <div class="section-title">Invoice Details</div>
                <div class="info-item">
                    <span class="info-label">Invoice Number:</span>
                    <span class="info-value">#{{ $order->order_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Invoice Date:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $order->order_date->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Due Date:</span>
                    <span class="info-value">{{ $order->created_at->addDays(7)->format('M d, Y') }}</span>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">Bill To</div>
                <div class="info-item">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value">{{ $order->shipping_address }}</span>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">Shop Information</div>
                <div class="info-item">
                    <span class="info-label">Shop Name:</span>
                    <span class="info-value">{{ $shop->shop_name ?? 'Wholesale Store' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact:</span>
                    <span class="info-value">{{ $shop->contact_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $shop->contact_email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value">{{ $shop->address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
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
            @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <div class="product-cell">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     alt="{{ $item->product_name }}"
                                     class="product-image">
                            @else
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-box text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <div class="product-name">{{ $item->product_name }}</div>
                                @if($item->product)
                                    <div class="product-sku">SKU: {{ $item->product->code }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="fw-bold">Rs. {{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>Rs. {{ number_format($order->shipping, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Tax:</span>
                <span>Rs. {{ number_format($order->tax, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Total Amount:</span>
                <span class="fw-bold">Rs. {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
            <div class="note-box">
                <div class="section-title">Order Notes</div>
                <p>{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <div class="payment-info">
            <div class="section-title">Payment Information</div>
            <div class="info-item mb-3">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Payment Status:</span>
                <span class="info-value badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
            </div>
        </div>

        <div class="text-muted">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated invoice. No signature required.</p>
            <p class="mt-3">
                <strong>Terms & Conditions:</strong><br>
                1. Goods sold are not returnable unless defective.<br>
                2. All disputes are subject to jurisdiction of local courts.<br>
                3. Prices are inclusive of all applicable taxes.
            </p>
        </div>

        <div class="mt-4 pt-4 border-top text-center">
            <p class="text-muted small">
                {{ $shop->shop_name ?? 'Wholesale Store' }}<br>
                {{ $shop->address ?? 'Address not specified' }}<br>
                Contact: {{ $shop->contact_phone ?? 'N/A' }} | Email: {{ $shop->contact_email ?? 'N/A' }}
            </p>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<button class="print-button no-print" onclick="window.print()">
    <i class="fas fa-print"></i>
    Print Invoice
</button>

<a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}" class="download-button no-print">
    <i class="fas fa-download"></i>
    Download PDF
</a>

<script>
    // Auto-print if ?print=1 in URL
    if (window.location.search.includes('print=1')) {
        window.print();
    }

    // Add page numbers for print
    @media print {
    @page {
            margin: 20mm;
        }

        body {
            font-size: 12pt;
        }

    .invoice-container {
            margin: 0;
        }

    .items-table {
            page-break-inside: avoid;
        }

    .total-section {
            page-break-inside: avoid;
        }
    }
</script>
</body>
</html>
