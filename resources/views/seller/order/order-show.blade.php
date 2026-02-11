@extends('seller.layout.app')
@push('title')
    Order Details - {{ $order->order_id }}
@endpush
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Order Details</h4>
                <h6>{{ $order->order_id }}</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Order Number</label>
                            <p class="form-control-static fw-bold">{{ $order->order_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Order Date</label>
                            <p class="form-control-static">{{ $order->order_date->format('F d, Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Platform</label>
                            <p class="form-control-static">{{ $order->platform ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Status</label>
                            <span class="badge
                                @if($order->status == 'complete') bg-success
                                @elseif($order->status == 'pending') bg-warning
                                @elseif($order->status == 'return') bg-danger
                                @elseif($order->status == 'rescheduling') bg-info
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        @if($order->invoice_no)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Invoice Number</label>
                                <p class="form-control-static">{{ $order->invoice_no }}</p>
                            </div>
                        @endif

                        @if($order->invoice_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Invoice Date</label>
                                <p class="form-control-static">{{ $order->invoice_date->format('F d, Y') }}</p>
                            </div>
                        @endif

                        @if($order->delivery_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Delivery Date</label>
                                <p class="form-control-static">{{ $order->delivery_date->format('F d, Y') }}</p>
                            </div>
                        @endif

                        @if($order->rescheduling_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Rescheduling Date</label>
                                <p class="form-control-static">{{ $order->rescheduling_date->format('F d, Y') }}</p>
                            </div>
                        @endif

                        @if($order->return_date)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Return Date</label>
                                <p class="form-control-static">{{ $order->return_date->format('F d, Y') }}</p>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Total Amount</label>
                            <p class="form-control-static fw-bold text-success">Rs. {{ number_format($order->total_amount, 2) }}</p>
                        </div>

                        @if($order->remark)
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Remark</label>
                                <p class="form-control-static">{{ $order->remark }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Customer Name</label>
                        <p class="form-control-static fw-bold">{{ $order->customer_name }}</p>
                    </div>

                    @if($order->customer_phone)
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="form-control-static">{{ $order->customer_phone }}</p>
                        </div>
                    @endif

                    @if($order->customer_address)
                        <div class="mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="form-control-static">{{ $order->customer_address }}</p>
                        </div>
                    @endif

                    @if($order->district)
                        <div class="mb-3">
                            <label class="form-label text-muted">District</label>
                            <p class="form-control-static">{{ $order->district }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Seller Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Seller Name</label>
                        <p class="form-control-static">{{ $order->seller->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Seller ID</label>
                        <p class="form-control-static">{{ $order->seller->code ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Uploaded At</label>
                        <p class="form-control-static">{{ $order->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
