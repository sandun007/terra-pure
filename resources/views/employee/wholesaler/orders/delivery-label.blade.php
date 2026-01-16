<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Label - Order #{{ $order->order_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-svg@1.1.0/dist/qrcode-svg.min.js"></script>
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-900);
            line-height: 1.5;
        }

        /* Print-specific styles */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }

            .print-controls {
                display: none !important;
            }

            .label-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(2, 1fr);
                width: 100%;
                height: 100vh;
                padding: 0;
            }

            .label {
                width: 100%;
                height: 100%;
                border: 1px dashed var(--gray-300);
                border-radius: 0;
                padding: 12mm;
                margin: 0;
                box-shadow: none;
                page-break-inside: avoid;
                overflow: hidden;
            }
        }

        /* Screen styles */
        @media screen {
            .print-controls {
                position: sticky;
                top: 0;
                z-index: 100;
                background-color: white;
                padding: 15px 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                margin-bottom: 25px;
                display: flex;
                justify-content: center;
                gap: 15px;
                flex-wrap: wrap;
            }

            .label-container {
                display: flex;
                flex-wrap: wrap;
                gap: 25px;
                justify-content: center;
                padding: 20px;
                max-width: 1400px;
                margin: 0 auto;
            }

            .label {
                width: 100%;
                max-width: 480px;
                border: 2px solid var(--primary-color);
                border-radius: 16px;
                padding: 30px;
                margin: 0;
                background: white;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .label:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            }

            .btn-print, .btn-close, .btn-copy, .btn-download {
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 10px;
                transition: all 0.2s ease;
            }

            .btn-print:hover {
                background: var(--primary-dark);
                transform: translateY(-2px);
            }

            .btn-close {
                background: var(--gray-700);
            }

            .btn-close:hover {
                background: var(--gray-900);
                transform: translateY(-2px);
            }

            .btn-copy {
                background: var(--secondary-color);
            }

            .btn-copy:hover {
                background: #0da271;
                transform: translateY(-2px);
            }

            .btn-download {
                background: var(--warning-color);
            }

            .btn-download:hover {
                background: #e68a00;
                transform: translateY(-2px);
            }

            .notification {
                position: fixed;
                top: 90px;
                right: 20px;
                background: var(--primary-color);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                display: none;
                z-index: 1000;
                font-weight: 500;
            }
        }

        /* Label styling - applies to both print and screen */
        .label {
            position: relative;
            overflow: hidden;
        }

        .label-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: 25px;
        }

        .label-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .label-icon {
            background-color: var(--primary-color);
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .label-title-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .label-title-text p {
            font-size: 14px;
            color: var(--gray-700);
            margin-top: 4px;
        }

        .order-number {
            background-color: var(--gray-100);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 18px;
        }

        .qr-section {
            display: flex;
            justify-content: center;
            margin: 25px 0;
            padding: 20px;
            background-color: var(--gray-50);
            border-radius: 12px;
            position: relative;
        }

        .qr-container {
            text-align: center;
            padding: 15px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 250px;
        }

        .qr-placeholder {
            width: 180px;
            height: 180px;
            background-color: var(--gray-100);
            margin: 0 auto 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--gray-700);
            border: 2px dashed var(--gray-300);
        }

        .qr-placeholder i {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .qr-info {
            text-align: center;
            margin-top: 10px;
        }

        .qr-info p {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 5px;
        }

        .qr-info .order-ref {
            font-size: 16px;
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: 1px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin: 25px 0;
        }

        .info-card {
            background-color: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
        }

        .info-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card h3 i {
            color: var(--primary-color);
        }

        .info-item {
            margin-bottom: 12px;
            display: flex;
            flex-wrap: wrap;
        }

        .info-label {
            font-weight: 600;
            color: var(--gray-700);
            min-width: 100px;
            font-size: 14px;
        }

        .info-value {
            color: var(--gray-900);
            font-weight: 500;
            flex: 1;
        }

        .address-box {
            background-color: white;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            font-size: 14px;
            line-height: 1.5;
        }

        .delivery-instructions {
            background-color: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            gap: 6px;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-delivered {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .notes-box {
            background-color: white;
            border-left: 4px solid var(--warning-color);
            padding: 15px;
            margin-top: 15px;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            margin-top: 25px;
            border-top: 2px solid var(--gray-200);
            font-size: 12px;
            color: var(--gray-700);
        }

        .footer-item {
            display: flex;
            flex-direction: column;
        }

        .footer-label {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .watermark {
            position: absolute;
            bottom: 10px;
            right: 10px;
            opacity: 0.1;
            font-size: 80px;
            font-weight: 900;
            color: var(--primary-color);
            transform: rotate(-15deg);
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .label {
                padding: 20px;
            }

            .footer {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .print-controls {
                flex-direction: column;
                align-items: center;
            }

            .btn-print, .btn-close, .btn-copy, .btn-download {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<!-- Notification for copy success -->
<div class="notification" id="copyNotification">
    <i class="fas fa-check-circle"></i> Order number copied to clipboard!
</div>

@if(!request()->has('auto-print'))
    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print Labels
        </button>
        <button class="btn-copy" onclick="copyOrderNumber()">
            <i class="fas fa-copy"></i> Copy Order #
        </button>
        <button class="btn-download" onclick="downloadAsPDF()">
            <i class="fas fa-download"></i> Save as PDF
        </button>
        <button class="btn-close" onclick="window.close()">
            <i class="fas fa-times"></i> Close Window
        </button>
    </div>
@endif

<div class="label-container">
    <div class="label">
        <!-- Watermark -->
        <div class="watermark">DELIVERY</div>

        <!-- Header -->
        <div class="label-header">
            <div class="label-title">
                <div class="label-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="label-title-text">
                    <h1>DELIVERY LABEL</h1>
                    <p>Track & Shipment Information</p>
                </div>
            </div>
            <div class="order-number" id="orderNumber">#{{ $order->order_number }}</div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-container">
                <div class="qr-placeholder" id="qrCode">
                    <div>
                        <i class="fas fa-qrcode"></i>
                        <p>Scan to track order</p>
                    </div>
                </div>
                <div class="qr-info">
                    <p>SCAN FOR DELIVERY DETAILS</p>
                    <div class="order-ref">ORDER: {{ $order->order_number }}</div>
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <!-- Order Details -->
            <div class="info-card">
                <h3><i class="fas fa-receipt"></i> Order Details</h3>
                <div class="info-item">
                    <span class="info-label">Order #:</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $order->created_at->format('F j, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Time:</span>
                    <span class="info-value">{{ $order->created_at->format('h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Items:</span>
                    <span class="info-value">{{ $order->items->count() }} item(s)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value" style="font-weight: 700; color: var(--primary-color);">Rs. {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="info-card">
                <h3><i class="fas fa-user"></i> Customer Details</h3>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->customer_email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Shipping Address:</span>
                </div>
                <div class="address-box">
                    {{ $order->shipping_address }}
                </div>
            </div>
        </div>

        <!-- Delivery Instructions -->
        <div class="delivery-instructions">
            <h3><i class="fas fa-truck"></i> Delivery Information</h3>
            <div class="info-grid" style="grid-template-columns: 1fr 1fr; margin-top: 15px; gap: 20px;">
                <div>
                    <div class="info-item">
                        <span class="info-label">Wholesaler:</span>
                        <span class="info-value">{{ $order->wholesaler->business_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Delivery Priority:</span>
                        <span class="info-value">Standard Delivery</span>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Order Status:</span>
                        <span class="info-value">
                                @php
                                    $statusClass = 'status-processing';
                                    if($order->status == 'pending') $statusClass = 'status-pending';
                                    elseif($order->status == 'processing') $statusClass = 'status-processing';
                                    elseif($order->status == 'shipped') $statusClass = 'status-shipped';
                                    elseif($order->status == 'delivered') $statusClass = 'status-delivered';
                                    elseif($order->status == 'cancelled') $statusClass = 'status-cancelled';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i>
                                    {{ strtoupper($order->status) }}
                                </span>
                            </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estimated Delivery:</span>
                        <span class="info-value">3-5 Business Days</span>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="notes-box">
                    <div class="info-label" style="margin-bottom: 8px;"><i class="fas fa-sticky-note"></i> Additional Notes:</div>
                    {{ $order->notes }}
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-item">
                <span class="footer-label">Generated By:</span>
                <span>{{ auth()->guard('employee')->user()->name ?? 'Employee' }}</span>
            </div>
            <div class="footer-item">
                <span class="footer-label">Printed On:</span>
                <span>{{ now()->format('F j, Y, h:i A') }}</span>
            </div>
            <div class="footer-item">
                <span class="footer-label">Tracking ID:</span>
                <span>TRK-{{ strtoupper(substr(md5($order->order_number), 0, 10)) }}</span>
            </div>
        </div>
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

<script>
    // Generate QR Code
    document.addEventListener('DOMContentLoaded', function() {
        // Generate a QR code for the order
        const orderNumber = "{{ $order->order_number }}";
        const qrData = `ORDER:${orderNumber}|TRACKING:TRK-{{ strtoupper(substr(md5($order->order_number), 0, 10)) }}|DATE:{{ $order->created_at->format('Y-m-d') }}`;

        // Simple QR code generation (in a real app, use a proper QR library)
        const qrContainer = document.getElementById('qrCode');
        qrContainer.innerHTML = `
                <div style="text-align: center;">
                    <div style="display: inline-block; padding: 10px; background: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 5px;">QR CODE</div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 10px;">Scan with delivery app</div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; display: inline-block;">
                            <div style="font-family: monospace; font-size: 18px; letter-spacing: 2px; color: #4f46e5;">{{ $order->order_number }}</div>
                        </div>
                    </div>
                </div>
            `;
    });

    // Copy order number to clipboard
    function copyOrderNumber() {
        const orderNumber = "{{ $order->order_number }}";
        navigator.clipboard.writeText(orderNumber).then(function() {
            showNotification('Order number copied to clipboard!');
        }, function(err) {
            // Fallback for older browsers
            const textArea = document.createElement("textarea");
            textArea.value = orderNumber;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showNotification('Order number copied to clipboard!');
        });
    }

    // Show notification
    function showNotification(message) {
        const notification = document.getElementById('copyNotification');
        notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        notification.style.display = 'block';

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000);
    }

    // Download as PDF (simulated - in a real app, use a PDF library)
    function downloadAsPDF() {
        showNotification('PDF download started...');
        // In a real application, you would generate a PDF here
        // For demo purposes, we'll simulate the download
        setTimeout(() => {
            showNotification('PDF downloaded successfully!');
        }, 1000);
    }

    // Generate multiple labels for printing (4 per page)
    window.onbeforeprint = function() {
        const labelContainer = document.querySelector('.label-container');
        const originalLabel = document.querySelector('.label');

        // Clear container
        labelContainer.innerHTML = '';

        // Create 4 identical labels for A4 printing
        for(let i = 0; i < 4; i++) {
            const clonedLabel = originalLabel.cloneNode(true);
            // Remove watermark from all but first label for cleaner print
            if(i > 0) {
                const watermark = clonedLabel.querySelector('.watermark');
                if(watermark) watermark.style.opacity = '0.05';
            }
            labelContainer.appendChild(clonedLabel);
        }
    };

    // Reset after printing
    window.onafterprint = function() {
        // Reload the page to show single label again
        if(!window.location.href.includes('auto-print')) {
            window.location.reload();
        }
    };
</script>
</body>
</html>
