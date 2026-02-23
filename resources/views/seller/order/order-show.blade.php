@extends('seller.layout.app')

@push('title')
    Order Details - {{ $order->order_id }}
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .detail-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .detail-card .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        .detail-card .card-title {
            margin: 0;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 500; min-width: 140px; }
        .info-value { font-weight: 600; color: #0f172a; text-align: right; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 999px;
            font-size: 13px; font-weight: 700; text-transform: capitalize;
        }
        .status-pending      { background: #fef3c7; color: #92400e; }
        .status-complete     { background: #d1fae5; color: #065f46; }
        .status-return       { background: #fee2e2; color: #991b1b; }
        .status-rescheduling { background: #dbeafe; color: #1e40af; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-title h4 { margin: 0; font-weight: 700; font-size: 22px; }
        .page-title h6 { margin: 4px 0 0; color: #64748b; font-family: monospace; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; background: #64748b; color: white;
            border: none; border-radius: 10px; font-weight: 600; font-size: 14px;
            text-decoration: none; transition: all .2s;
        }
        .btn-back:hover { background: #475569; color: white; transform: translateY(-1px); }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">
                <h4>
                    <span class="material-icons-round me-2" style="vertical-align:middle;color:#4f46e5">receipt_long</span>
                    Order Details
                </h4>
                <h6>{{ $order->order_id }}</h6>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url()->previous() }}" class="btn-back">
                    <span class="material-icons-round" style="font-size:18px">arrow_back</span>
                    Back
                </a>
                <a href="{{ route('seller.pending-order') }}" class="btn-back" style="background:#4f46e5">
                    <span class="material-icons-round" style="font-size:18px">list</span>
                    All Orders
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- Order Information --}}
            <div class="col-lg-8">
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="material-icons-round" style="color:#4f46e5">receipt</span>
                            Order Information
                        </h5>
                    </div>
                    <div class="card-body px-4 py-3">

                        <div class="info-row">
                            <span class="info-label">Order Number</span>
                            <span class="info-value" style="font-family:monospace">{{ $order->order_id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Order Date</span>
                            <span class="info-value">{{ $order->order_date->format('F d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Platform</span>
                            <span class="info-value">{{ $order->platform ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Call Status</span>
                            <span class="info-value">{{ $order->call_status ?? 'Pending Call' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Status</span>
                            <span class="info-value">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                        </div>

                        @if($order->delivery_date)
                            <div class="info-row">
                                <span class="info-label">Delivery Date</span>
                                <span class="info-value">{{ $order->delivery_date->format('F d, Y') }}</span>
                            </div>
                        @endif

                        @if($order->rescheduling_date)
                            <div class="info-row">
                                <span class="info-label">Rescheduling Date</span>
                                <span class="info-value">{{ $order->rescheduling_date->format('F d, Y') }}</span>
                            </div>
                        @endif

                        @if($order->return_date)
                            <div class="info-row">
                                <span class="info-label">Return Date</span>
                                <span class="info-value">{{ $order->return_date->format('F d, Y') }}</span>
                            </div>
                        @endif

                        <div class="info-row">
                            <span class="info-label">Quantity</span>
                            <span class="info-value">{{ $order->quantity ?? 1 }} unit(s)</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Unit Price</span>
                            <span class="info-value">Rs. {{ number_format($order->product_price, 2) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Amount</span>
                            <span class="info-value" style="color:#10b981;font-size:18px">
                            Rs. {{ number_format($order->total_amount, 2) }}
                        </span>
                        </div>

                        @if($order->remark)
                            <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:6px">
                                <span class="info-label">Remark / Notes</span>
                                <span class="info-value text-start" style="color:#475569; font-weight:400">{{ $order->remark }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product Details --}}
                @if($order->product_name)
                    <div class="card detail-card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="material-icons-round" style="color:#f59e0b">inventory_2</span>
                                Product Details
                            </h5>
                        </div>
                        <div class="card-body px-4 py-3 d-flex gap-3 align-items-center">
                            @if($order->product_image)
                                <img src="{{ asset('storage/' . $order->product_image) }}"
                                     alt="{{ $order->product_name }}"
                                     style="width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid #e2e8f0"
                                     onerror="this.style.display='none'">
                            @endif
                            <div>
                                <div style="font-weight:700;font-size:16px;color:#0f172a">{{ $order->product_name }}</div>
                                <div style="font-family:monospace;font-size:12px;background:#f1f5f9;padding:3px 10px;border-radius:6px;margin-top:4px;display:inline-block">
                                    {{ $order->product_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar: Customer + Seller --}}
            <div class="col-lg-4">

                {{-- Customer Information --}}
                <div class="card detail-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="material-icons-round" style="color:#10b981">person</span>
                            Customer Information
                        </h5>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value">{{ $order->customer_name }}</span>
                        </div>
                        @if($order->customer_phone)
                            <div class="info-row">
                                <span class="info-label">Phone</span>
                                <span class="info-value">{{ $order->customer_phone }}</span>
                            </div>
                        @endif
                        @if($order->customer_email)
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ $order->customer_email }}</span>
                            </div>
                        @endif
                        @if($order->district)
                            <div class="info-row">
                                <span class="info-label">District</span>
                                <span class="info-value">{{ $order->district }}</span>
                            </div>
                        @endif
                        @if($order->customer_address)
                            <div class="info-row" style="flex-direction:column;align-items:flex-start;gap:4px">
                                <span class="info-label">Address</span>
                                <span class="info-value text-start" style="font-weight:400;color:#475569">{{ $order->customer_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Seller Information --}}
                <div class="card detail-card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="material-icons-round" style="color:#8b5cf6">store</span>
                            Seller Information
                        </h5>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="info-row">
                            <span class="info-label">Seller Name</span>
                            <span class="info-value">{{ $order->seller->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Seller ID / Code</span>
                            <span class="info-value">{{ $order->seller->code ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Created At</span>
                            <span class="info-value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ $order->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
