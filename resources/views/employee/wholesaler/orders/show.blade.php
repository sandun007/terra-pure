@extends('employee.layout.app')
@push('title')
    Order #{{ $order->order_number }} | Employee Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: #f8fafc;
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .header-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .order-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .breadcrumb-text {
            color: #64748b;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .btn-back {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        /* Status Hero Card */
        .status-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2.5rem;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .status-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .status-hero-content {
            position: relative;
            z-index: 1;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .status-info h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-pill {
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            animation: statusPulse 2s ease-in-out infinite;
        }

        @keyframes statusPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .status-pending { background: #fbbf24; color: #78350f; }
        .status-confirmed { background: #60a5fa; color: #1e3a8a; }
        .status-processing { background: #a78bfa; color: #4c1d95; }
        .status-shipped { background: #34d399; color: #064e3b; }
        .status-completed { background: #10b981; color: #064e3b; }
        .status-cancelled { background: #f87171; color: #7f1d1d; }

        .status-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            opacity: 0.95;
            margin-top: 0.75rem;
        }

        .amount-display {
            text-align: right;
        }

        .amount-display h3 {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .amount-display p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
        }

        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border-color: #e2e8f0;
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-purple { background: linear-gradient(135deg, #a78bfa, #8b5cf6); color: white; }
        .icon-blue { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: white; }
        .icon-green { background: linear-gradient(135deg, #34d399, #10b981); color: white; }
        .icon-orange { background: linear-gradient(135deg, #fb923c, #f97316); color: white; }

        .card-header-custom h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .info-grid {
            display: grid;
            gap: 0.875rem;
        }

        .info-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 1rem;
            padding: 0.5rem 0;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.9rem;
        }

        .info-value {
            color: #0f172a;
            font-weight: 500;
            word-break: break-word;
        }

        /* Table Section */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            margin-top: 1.5rem;
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
        }

        .table-modern thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .table-modern td {
            padding: 1.25rem 1rem;
            color: #334155;
        }

        .product-name {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .product-sku {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .table-modern tfoot {
            border-top: 2px solid #e2e8f0;
        }

        .table-modern tfoot tr {
            background: #f8fafc;
        }

        .table-modern tfoot td {
            padding: 1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .table-modern tfoot tr:last-child {
            background: linear-gradient(135deg, #667eea15, #764ba215);
        }

        .table-modern tfoot tr:last-child td {
            font-size: 1.1rem;
            font-weight: 800;
            color: #667eea;
        }

        /* Status Update Section */
        .status-update-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-top: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 2px solid #f1f5f9;
        }

        .status-update-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .status-update-header h5 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .status-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-status {
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-status:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-confirm {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-process {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-ship {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn-complete {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .payment-badge {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }

            .header-title h1 {
                font-size: 1.5rem;
            }

            .status-row {
                flex-direction: column;
            }

            .amount-display {
                text-align: left;
            }

            .amount-display h3 {
                font-size: 2rem;
            }

            .info-cards {
                grid-template-columns: 1fr;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .table-card {
                overflow-x: auto;
            }

            .status-buttons {
                flex-direction: column;
            }

            .btn-status {
                width: 100%;
                justify-content: center;
            }
        }

        /* Notification Toast */
        .toast-notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: white;
            padding: 1.25rem 1.75rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            display: none;
            align-items: center;
            gap: 1rem;
            z-index: 10000;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-notification.success {
            border-color: #10b981;
        }

        .toast-notification.error {
            border-color: #ef4444;
        }

        .toast-notification.active {
            display: flex;
        }
    </style>
@endpush

@section('content')
    <div class="page-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-title">
                <h1>
                    <span class="material-icons-outlined" style="font-size: 2rem;">receipt_long</span>
                    Order <span class="order-badge">#{{ $order->order_number }}</span>
                </h1>
                <div class="breadcrumb-text">
                    <span class="material-icons-outlined" style="font-size: 1rem;">home</span>
                    Dashboard / Orders / Details
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn-modern btn-back">
                    <span class="material-icons-outlined">arrow_back</span>
                    Back to Orders
                </a>
                <a href="{{ route('employee.wholesaler_orders.invoice.download', $order->id) }}" class="btn-modern btn-primary-modern">
                    <span class="material-icons-outlined">download</span>
                    Download Invoice
                </a>
            </div>
        </div>

        <!-- Status Hero Card -->
        <div class="status-hero">
            <div class="status-hero-content">
                <div class="status-row">
                    <div class="status-info">
                        <h2>
                            Order Status:
                            <span class="status-pill status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </h2>
                        <div class="status-meta">
                            <span class="material-icons-outlined">event</span>
                            {{ $order->created_at->format('F d, Y') }} at {{ $order->created_at->format('h:i A') }}
                        </div>
                    </div>
                    <div class="amount-display">
                        <h3>Rs. {{ number_format($order->total_amount, 2) }}</h3>
                        <p>Total Order Amount</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards Grid -->
        <div class="info-cards">
            <!-- Wholesaler Information -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-icon icon-purple">
                        <span class="material-icons-outlined">store</span>
                    </div>
                    <h5>Wholesaler Information</h5>
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Business</div>
                        <div class="info-value">{{ $order->wholesaler->business_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Owner</div>
                        <div class="info-value">{{ $order->wholesaler->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->wholesaler->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $order->wholesaler->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-icon icon-blue">
                        <span class="material-icons-outlined">person</span>
                    </div>
                    <h5>Customer Information</h5>
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $order->customer_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $order->customer_email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $order->shipping_address }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-icon icon-green">
                        <span class="material-icons-outlined">payments</span>
                    </div>
                    <h5>Payment Details</h5>
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Method</div>
                        <div class="info-value">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="payment-badge badge-{{ $order->payment_status == 'paid' ? 'paid' : 'pending' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    @if($order->notes)
                        <div class="info-row">
                            <div class="info-label">Notes</div>
                            <div class="info-value">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="table-card">
            <div class="card-header-custom">
                <div class="card-icon icon-orange">
                    <span class="material-icons-outlined">shopping_cart</span>
                </div>
                <h5>Order Items</h5>
            </div>
            <div style="overflow-x: auto;">
                <table class="table-modern">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Unit Price</th>
                        <th style="text-align: center;">Quantity</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="product-name">{{ $item->product_name }}</div>
                                @if($item->product)
                                    <div class="product-sku">SKU: {{ $item->product->code }}</div>
                                @endif
                            </td>
                            <td style="text-align: center;">Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td style="text-align: center;"><strong>{{ $item->quantity }}</strong></td>
                            <td style="text-align: right; font-weight: 700;">Rs. {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Subtotal:</td>
                        <td style="text-align: right;">Rs. {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">Tax ({{ $order->tax_rate ?? 5 }}%):</td>
                        <td style="text-align: right;">Rs. {{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">Shipping:</td>
                        <td style="text-align: right;">Rs. {{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total Amount:</td>
                        <td style="text-align: right;">Rs. {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Status Update Section -->
        @if(in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped']))
            <div class="status-update-section">
                <div class="status-update-header">
                    <span class="material-icons-outlined" style="font-size: 1.75rem; color: #667eea;">sync</span>
                    <h5>Update Order Status</h5>
                </div>
                <div class="status-buttons">
                    @if($order->status == 'pending')
                        <button onclick="updateStatus('confirmed')" class="btn-status btn-confirm">
                            <span class="material-icons-outlined">check_circle</span>
                            Confirm Order
                        </button>
                    @endif
                    @if($order->status == 'confirmed')
                        <button onclick="updateStatus('processing')" class="btn-status btn-process">
                            <span class="material-icons-outlined">autorenew</span>
                            Start Processing
                        </button>
                    @endif
                    @if($order->status == 'processing')
                        <button onclick="updateStatus('shipped')" class="btn-status btn-ship">
                            <span class="material-icons-outlined">local_shipping</span>
                            Mark as Shipped
                        </button>
                    @endif
                    @if($order->status == 'shipped')
                        <button onclick="updateStatus('completed')" class="btn-status btn-complete">
                            <span class="material-icons-outlined">done_all</span>
                            Mark as Completed
                        </button>
                    @endif
                    @if(!in_array($order->status, ['completed', 'cancelled']))
                        <button onclick="updateStatus('cancelled')" class="btn-status btn-cancel">
                            <span class="material-icons-outlined">cancel</span>
                            Cancel Order
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-notification" id="toastNotification">
        <span class="material-icons-outlined" id="toastIcon"></span>
        <span id="toastMessage"></span>
    </div>
@endsection

@push('script')
    <script>
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const icon = document.getElementById('toastIcon');
            const msg = document.getElementById('toastMessage');

            toast.className = `toast-notification ${type}`;
            icon.textContent = type === 'success' ? 'check_circle' : 'error';
            msg.textContent = message;
            toast.classList.add('active');

            setTimeout(() => {
                toast.classList.remove('active');
            }, 4000);
        }

        function updateStatus(newStatus) {
            const statusMessages = {
                'confirmed': 'confirm',
                'processing': 'start processing',
                'shipped': 'mark as shipped',
                'completed': 'mark as completed',
                'cancelled': 'cancel'
            };

            const actionText = statusMessages[newStatus] || 'update';

            if (confirm(`Are you sure you want to ${actionText} this order?`)) {
                showLoading();

                fetch('{{ route("employee.wholesaler_orders.updateStatus", $order->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        notes: `Status updated to ${newStatus} by employee`
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showToast('Order status updated successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast(data.message || 'Failed to update status', 'error');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        showToast('Error updating order status', 'error');
                    });
            }
        }

        // Smooth scroll to top on page load
        window.addEventListener('load', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Add print functionality
        function printOrder() {
            window.print();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                printOrder();
            }
            // ESC to go back
            if (e.key === 'Escape') {
                window.location.href = '{{ route("employee.wholesaler_orders.index") }}';
            }
        });
    </script>
@endpush
