@extends('employee.layout.app')
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

        .invoice-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: flex-end;
        }
    </style>
@endpush

@section('content')
    <div class="invoice-view">
        <div class="invoice-paper">
            <div class="invoice-actions">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i> Print Invoice
                </button>
                <a href="{{ route('employee.wholesaler_orders.invoice.download', $order->id) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i> Download PDF
                </a>
                <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Order
                </a>
            </div>

            <!-- Include the invoice content -->
            <div style="padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: bold; color: #1e293b; margin: 0 0 10px 0;">
                            {{ $order->wholesaler->business_name ?? 'Wholesale Business' }}
                        </h1>
                        <p style="margin: 5px 0;">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $order->wholesaler->address ?? 'Address not available' }}
                        </p>
                        <p style="margin: 5px 0;">
                            <i class="fas fa-phone me-2"></i>
                            {{ $order->wholesaler->phone ?? 'Phone not available' }}
                        </p>
                        <p style="margin: 5px 0;">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $order->wholesaler->email ?? 'Email not available' }}
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <h2 style="font-size: 28px; color: #6366f1; margin: 0 0 10px 0;">INVOICE</h2>
                        <p style="margin: 5px 0;"><strong>#{{ $order->order_number }}</strong></p>
                        <p style="margin: 5px 0;">Date: {{ $order->created_at->format('F d, Y') }}</p>
                        <p style="margin: 5px 0;">Status:
                            <span style="background: {{ $order->status == 'completed' ? '#dcfce7' : '#fef3c7' }};
                                      color: {{ $order->status == 'completed' ? '#166534' : '#92400e' }};
                                      padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Customer Details -->
                <div style="margin-bottom: 30px;">
                    <h4 style="color: #475569; margin-bottom: 15px;">Bill To</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p style="margin: 5px 0;"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p style="margin: 5px 0;"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <p style="margin: 5px 0;"><strong>Shipping Address:</strong></p>
                            <p style="margin: 5px 0;">{{ $order->shipping_address }}</p>
                            <p style="margin: 5px 0;"><strong>Payment Method:</strong>
                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div style="margin: 30px 0;">
                    <h4 style="color: #475569; margin-bottom: 15px;">Order Items</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #e2e8f0;">#</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #e2e8f0;">Description</th>
                            <th style="padding: 12px 15px; text-align: right; border-bottom: 2px solid #e2e8f0;">Unit Price</th>
                            <th style="padding: 12px 15px; text-align: center; border-bottom: 2px solid #e2e8f0;">Qty</th>
                            <th style="padding: 12px 15px; text-align: right; border-bottom: 2px solid #e2e8f0;">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #e2e8f0;">{{ $index + 1 }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #e2e8f0;">{{ $item->product_name }}</td>
                                <td style="padding: 12px 15px; text-align: right; border-bottom: 1px solid #e2e8f0;">
                                    Rs. {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td style="padding: 12px 15px; text-align: center; border-bottom: 1px solid #e2e8f0;">
                                    {{ $item->quantity }}
                                </td>
                                <td style="padding: 12px 15px; text-align: right; border-bottom: 1px solid #e2e8f0;">
                                    Rs. {{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 30px;">
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #cbd5e1;">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #cbd5e1;">
                        <span>Tax ({{ $order->tax_rate ?? 5 }}%):</span>
                        <span>Rs. {{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #cbd5e1;">
                        <span>Shipping:</span>
                        <span>Rs. {{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 18px; font-weight: bold;">
                        <span>TOTAL:</span>
                        <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                @if($order->notes)
                    <div style="margin-top: 30px; padding: 15px; background: #fef3c7; border-radius: 6px;">
                        <strong>Notes:</strong> {{ $order->notes }}
                    </div>
                @endif

                <!-- Footer -->
                <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 11px;">
                    <p>Thank you for your business!</p>
                    <p>Invoice generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
                    <p>This is a computer-generated invoice. No signature required.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
