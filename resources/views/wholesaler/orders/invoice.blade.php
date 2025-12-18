<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 10px 0;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 28px;
            color: #6366f1;
            margin: 0 0 10px 0;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #475569;
        }

        .detail-value {
            flex: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .summary-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 11px;
        }

        .note {
            margin-top: 30px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 6px;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ auth()->guard('wholesaler')->user()->business_name ?? 'Wholesale Business' }}</h1>
            <p>
                {{ auth()->guard('wholesaler')->user()->address ?? '' }}<br>
                Phone: {{ auth()->guard('wholesaler')->user()->phone ?? '' }}<br>
                Email: {{ auth()->guard('wholesaler')->user()->email ?? '' }}
            </p>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>#{{ $order->order_number }}</strong></p>
            <p>Date: {{ $order->created_at->format('F d, Y') }}</p>
            <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="invoice-details">
        <div class="detail-row">
            <div class="detail-label">Bill To:</div>
            <div class="detail-value">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_email }}<br>
                {{ $order->customer_phone }}
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Shipping Address:</div>
            <div class="detail-value">{{ $order->shipping_address }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Payment Method:</div>
            <div class="detail-value">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th class="text-right">Unit Price</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">Rs. {{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Tax:</span>
            <span>Rs. {{ number_format($order->tax, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Shipping:</span>
            <span>Rs. {{ number_format($order->shipping, 2) }}</span>
        </div>
        <div class="summary-row summary-total">
            <span>TOTAL:</span>
            <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    @if($order->notes)
        <div class="note">
            <strong>Notes:</strong> {{ $order->notes }}
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Invoice generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated invoice. No signature required.</p>
    </div>
</div>
</body>
</html>
