<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order #{{ $order->order_number }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0.5cm;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 20px;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .order-info {
            margin-bottom: 20px;
        }

        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-info th, .order-info td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-info th {
            background-color: #f5f5f5;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        .items-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        .total-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            margin: 5px 0;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
            margin-top: 10px;
        }

        .print-controls {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .print-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }

        .print-btn:hover {
            background: #0056b3;
        }

        .close-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }

        .close-btn:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
<div class="print-controls no-print">
    <h3>Print Order #{{ $order->order_number }}</h3>
    <button class="print-btn" onclick="window.print()">
        🖨️ Print Now
    </button>
    <button class="close-btn" onclick="window.close()">
        ✖️ Close Window
    </button>
    <p class="text-muted">This page will auto-print. If it doesn't, click the print button above.</p>
</div>

<div class="print-container">
    <div class="header">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
        <h2>ORDER RECEIPT</h2>
        <h3>Order #: {{ $order->order_number }}</h3>
        <p>Date: {{ $order->created_at->format('F d, Y h:i A') }}</p>
    </div>

    <div class="order-info">
        <table>
            <tr>
                <th colspan="2" style="text-align: center; background-color: #e9ecef;">Customer Information</th>
            </tr>
            <tr>
                <th width="30%">Customer Name</th>
                <td>{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $order->customer_email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $order->customer_phone }}</td>
            </tr>
            <tr>
                <th>Shipping Address</th>
                <td>{{ $order->shipping_address }}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</td>
            </tr>
            <tr>
                <th>Wholesaler</th>
                <td>{{ $order->wholesaler->business_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Order Status</th>
                <td>
                        <span style="
                            padding: 4px 12px;
                            border-radius: 20px;
                            font-weight: bold;
                            background: {{ $order->status == 'completed' ? '#d4edda' :
                                        ($order->status == 'pending' ? '#fff3cd' :
                                        ($order->status == 'processing' ? '#d1ecf1' :
                                        ($order->status == 'shipped' ? '#cce5ff' :
                                        ($order->status == 'cancelled' ? '#f8d7da' : '#f5f5f5')))) }};
                            color: {{ $order->status == 'completed' ? '#155724' :
                                    ($order->status == 'pending' ? '#856404' :
                                    ($order->status == 'processing' ? '#0c5460' :
                                    ($order->status == 'shipped' ? '#004085' :
                                    ($order->status == 'cancelled' #721c24' : '#333')))) }};
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                </td>
            </tr>
        </table>
    </div>

    <h4>Order Items</h4>
    <table class="items-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                <td>Rs. {{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <strong>Subtotal:</strong> Rs. {{ number_format($order->subtotal, 2) }}
        </div>
        <div class="total-row">
            <strong>Tax:</strong> Rs. {{ number_format($order->tax, 2) }}
        </div>
        <div class="total-row">
            <strong>Shipping:</strong> Rs. {{ number_format($order->shipping, 2) }}
        </div>
        <div class="total-row grand-total">
            <strong>GRAND TOTAL:</strong> Rs. {{ number_format($order->total_amount, 2) }}
        </div>
    </div>

    <div style="margin-top: 40px; border-top: 1px dashed #ccc; padding-top: 20px; font-size: 11px; text-align: center; color: #666;">
        <p>Thank you for your business!</p>
        <p>Printed on: {{ now()->format('F d, Y h:i A') }}</p>
        <p>Printed by: Employee Dashboard</p>
    </div>
</div>

<script>
    // Auto-print when page loads
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);

        // Close window after printing (optional)
        window.onafterprint = function() {
            setTimeout(function() {
                // window.close(); // Uncomment if you want to auto-close
            }, 1000);
        };
    };
</script>
</body>
</html>
