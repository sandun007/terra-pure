<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Label - Order #{{ $order->order_number }}</title>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                font-size: 10px;
            }
            .no-print {
                display: none !important;
            }
            .label {
                width: 48%;
                height: 48%;
                float: left;
                margin: 1%;
                border: 1px solid #000;
                padding: 5px;
                box-sizing: border-box;
                page-break-inside: avoid;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        @media screen {
            .label-container {
                max-width: 800px;
                margin: 0 auto;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .label {
                width: 48%;
                border: 1px solid #ccc;
                padding: 15px;
                background: white;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
        }

        .print-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .info-section {
            margin: 5px 0;
        }

        .info-row {
            display: flex;
            margin: 2px 0;
        }

        .info-label {
            font-weight: bold;
            min-width: 80px;
        }

        .barcode-area {
            text-align: center;
            margin: 10px 0;
            padding: 10px;
            border: 1px dashed #000;
        }

        .print-controls {
            text-align: center;
            margin: 20px;
            padding: 20px;
            background: white;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-print {
            background: #4CAF50;
            color: white;
        }

        .btn-close {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
<div class="print-controls no-print">
    <h3>Print Delivery Label</h3>
    <p>Order #{{ $order->order_number }} - {{ $order->customer_name }}</p>
    <button class="btn-print" onclick="window.print()">🖨️ Print Now</button>
    <button class="btn-close" onclick="window.close()">✖️ Close</button>
</div>

<div class="label-container">
    <div class="label">
        <div class="print-header">
            <h3>DELIVERY LABEL</h3>
            <h4>Order #{{ $order->order_number }}</h4>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">From:</div>
                <div>{{ $wholesaler->business_name ?? 'Company' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div>{{ $wholesaler->phone ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">To:</div>
                <div>{{ $order->customer_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div>{{ $order->shipping_address }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div>{{ $order->customer_phone }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Items:</div>
                <div>{{ $order->items->count() }} items</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total:</div>
                <div>Rs. {{ number_format($order->total_amount, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div>{{ $order->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="barcode-area">
            <div style="font-size: 8px; margin-bottom: 5px;">SCAN FOR TRACKING</div>
            <div style="font-family: monospace; letter-spacing: 2px;">|||| |||| |||| ||||</div>
            <div style="font-size: 8px; margin-top: 5px;">{{ $order->order_number }}</div>
        </div>

        <div style="text-align: center; font-size: 8px; margin-top: 5px;">
            <div>Handle with care | Fragile</div>
            <div>Printed: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <!-- Duplicate label for multiple copies -->
    <div class="label">
        <div class="print-header">
            <h3>DELIVERY LABEL</h3>
            <h4>Order #{{ $order->order_number }}</h4>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">From:</div>
                <div>{{ $wholesaler->business_name ?? 'Company' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div>{{ $wholesaler->phone ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">To:</div>
                <div>{{ $order->customer_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div>{{ $order->shipping_address }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div>{{ $order->customer_phone }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Items:</div>
                <div>{{ $order->items->count() }} items</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total:</div>
                <div>Rs. {{ number_format($order->total_amount, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div>{{ $order->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="barcode-area">
            <div style="font-size: 8px; margin-bottom: 5px;">SCAN FOR TRACKING</div>
            <div style="font-family: monospace; letter-spacing: 2px;">|||| |||| |||| ||||</div>
            <div style="font-size: 8px; margin-top: 5px;">{{ $order->order_number }}</div>
        </div>

        <div style="text-align: center; font-size: 8px; margin-top: 5px;">
            <div>Handle with care | Fragile</div>
            <div>Printed: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>

<script>
    // Auto print on page load
    window.onload = function() {
        // Small delay to ensure everything is loaded
        setTimeout(function() {
            window.print();
        }, 1000);
    };

    // Close window after printing
    window.onafterprint = function() {
        setTimeout(function() {
            // Optional: Uncomment to auto-close
            // window.close();
        }, 1000);
    };
</script>
</body>
</html>
