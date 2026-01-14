<!DOCTYPE html>
<html lang="en">
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

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-processing { background: #f3e8ff; color: #5b21b6; }
        .status-shipped { background: #dcfce7; color: #166534; }
        .status-completed { background: #10b981; color: white; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ config('app.name', 'Laravel') }}</h1>
            <p>
                123 Business Street<br>
                City, State 12345<br>
                Phone: (123) 456-7890<br>
                Email: info@company.com
            </p>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>#{{ $order->order_number }}</strong></p>
            <p>Date: {{ $order->created_at->format('F d, Y') }}</p>
            <p>Status: <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
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
        <div class="detail-row">
            <div class="detail-label">Wholesaler:</div>
            <div class="detail-value">{{ $order->wholesaler->business_name ?? 'N/A' }}</div>
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
