@extends('employee.layout.app')

@push('title')
    Print Selected Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .print-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .orders-summary {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .summary-item {
            text-align: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
        }

        .summary-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .orders-list {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .order-item {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .order-item:hover {
            background: #f8fafc;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .order-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-print-single {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-print-single:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #64748b;
        }

        .detail-value {
            font-weight: 600;
            color: #1e293b;
        }

        .print-actions {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-print-all {
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-weight: 700;
            font-size: 1.125rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }

        .btn-print-all:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-back {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        @media print {
            .page-header,
            .print-actions,
            .btn-print-single,
            .btn-back {
                display: none !important;
            }

            .order-item {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .orders-list {
                box-shadow: none;
                border: none;
            }

            .order-item {
                border: 1px solid #e2e8f0;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .print-actions {
                flex-direction: column;
            }

            .btn-print-all,
            .btn-back {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="print-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mb-2">📄 Print Selected Orders</h1>
                    <p class="mb-0 opacity-90">Review and print selected wholesaler orders</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                        <i class="material-icons-outlined me-1">arrow_back</i>
                        Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders Summary -->
        <div class="orders-summary">
            <h3 class="mb-3">Print Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Orders</div>
                    <div class="summary-value">{{ $orders->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Items</div>
                    <div class="summary-value">{{ $orders->sum(function($order) { return $order->items->count(); }) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Amount</div>
                    <div class="summary-value">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Avg. Order Value</div>
                    <div class="summary-value">Rs. {{ number_format($orders->avg('total_amount'), 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-title">
                            <i class="material-icons-outlined">receipt_long</i>
                            Order #{{ $order->order_number }}
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('employee.wholesaler.print.out', $order->id) }}" target="_blank"
                               class="btn-print-single">
                                <i class="material-icons-outlined">print</i>
                                Print This Order
                            </a>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-item">
                            <span class="detail-label">Wholesaler</span>
                            <span class="detail-value">{{ $order->wholesaler->business_name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Customer</span>
                            <span class="detail-value">{{ $order->customer_name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date</span>
                            <span class="detail-value">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Items</span>
                            <span class="detail-value">{{ $order->items->count() }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total</span>
                            <span class="detail-value text-success">Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    @if($order->items->count() > 0)
                        <div class="mt-3">
                            <h6 class="mb-2">Order Items:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                                            <td>Rs. {{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Print Actions -->
        <div class="print-actions">
            <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn-back">
                <i class="material-icons-outlined">arrow_back</i>
                Back to Orders
            </a>

            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn-print-all">
                    <i class="material-icons-outlined">print</i>
                    Print All Orders
                </button>

                <button onclick="confirmPrint()" class="btn-print-all" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="material-icons-outlined">done_all</i>
                    Mark as Printed
                </button>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmPrint() {
            Swal.fire({
                title: 'Mark as Printed?',
                text: 'Are you sure you want to mark all selected orders as printed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Mark as Printed',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('{{ route("employee.wholesaler_orders.print.selected") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: `${result.value.count} orders marked as printed successfully.`,
                        icon: 'success',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.href = '{{ route("employee.wholesaler_orders.index") }}';
                    });
                }
            });
        }

        // Keyboard shortcut for printing
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
@endpush
