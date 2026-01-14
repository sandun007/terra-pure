<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Label - Order #{{ $order->order_number }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .label-container {
                display: flex;
                flex-wrap: wrap;
                padding: 10mm;
            }
            .label {
                width: 50%;
                height: 25%;
                box-sizing: border-box;
                padding: 10mm;
                border: 1px dashed #ccc;
                page-break-inside: avoid;
            }
            table {
                width: 100%;
                font-size: 14px;
                border-collapse: collapse;
            }
            th, td {
                vertical-align: top;
                padding: 5px;
                border: 1px solid #ddd;
            }
            .header {
                background: #f8f9fa;
                font-weight: bold;
                text-align: center;
            }
            .qr-code {
                text-align: center;
                margin: 10px 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .print-controls {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-print {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-print:hover {
            background: #4338ca;
        }

        @media screen {
            .label-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }

            .label {
                width: 400px;
                border: 2px solid #4f46e5;
                border-radius: 10px;
                padding: 20px;
                margin: 10px;
                background: white;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
@if(!request()->has('auto-print'))
    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Delivery Label
        </button>
        <button class="btn-print" onclick="window.close()" style="background: #6b7280; margin-left: 10px;">
            ✕ Close
        </button>
    </div>
@endif

<div class="label-container">
    <div class="label">
        <table>
            <thead>
            <tr>
                <th colspan="2" class="header">📦 DELIVERY LABEL</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <div class="qr-code">
                        <div style="border: 2px solid #000; padding: 10px; display: inline-block; margin: 10px 0;">
                            <strong>SCAN FOR DELIVERY</strong><br>
                            <div style="font-size: 24px; margin: 10px 0;">⬛ ⬜ ⬛</div>
                            <div style="font-size: 11px;">ORDER: {{ $order->order_number }}</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Order Details</th>
                <th>Customer Details</th>
            </tr>
            <tr>
                <td>
                    <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                    <p><strong>Items:</strong> {{ $order->items->count() }}</p>
                    <p><strong>Total:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
                </td>
                <td>
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Address:</strong></p>
                    <p style="font-size: 12px;">{{ $order->shipping_address }}</p>
                </td>
            </tr>
            <tr>
                <th colspan="2">Delivery Instructions</th>
            </tr>
            <tr>
                <td colspan="2">
                    <p><strong>Wholesaler:</strong> {{ $order->wholesaler->business_name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> <span style="background: #d1fae5; padding: 2px 8px; border-radius: 10px;">{{ strtoupper($order->status) }}</span></p>
                    @if($order->notes)
                        <p><strong>Notes:</strong> {{ $order->notes }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-size: 10px; padding: 15px 0;">
                    <hr>
                    <p>Generated by: {{ auth()->guard('employee')->user()->name ?? 'Employee' }}</p>
                    <p>Printed on: {{ now()->format('Y-m-d H:i') }}</p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

@if(request()->has('auto-print'))
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 1000);
        };
    </script>
@endif
</body>
</html>
