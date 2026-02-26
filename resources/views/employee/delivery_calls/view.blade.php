@extends('employee.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Delivery Call #{{ $call->id }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div><b>Call Number:</b> {{ $call->call_number }}</div>
                        <div><b>Order Code:</b> {{ $call->order_code ?? '-' }}</div>
                        <div><b>Date:</b> {{ optional($call->date)->format('Y-m-d') }}</div>
                        <div><b>Status:</b>
                            <span class="badge {{ $call->status_badge_class }}">{{ $call->status }}</span>
                        </div>
                        <div><b>Status Date:</b> {{ optional($call->status_date)->format('Y-m-d H:i') }}</div>
                    </div>

                    <div class="col-md-6">
                        <div><b>Customer:</b> {{ $call->customer_name }}</div>
                        <div><b>Phone 1:</b> {{ $call->customer_phone_01 }}</div>
                        <div><b>Phone 2:</b> {{ $call->customer_phone_02 ?? '-' }}</div>
                        <div><b>Address:</b> {{ $call->customer_address ?? '-' }}</div>
                        <div><b>City/District:</b> {{ $call->city }} / {{ $call->district }}</div>
                    </div>

                    <div class="col-md-12"><hr></div>

                    <div class="col-md-6">
                        <div><b>Item:</b> {{ $call->item_name }}</div>
                        <div><b>Qty:</b> {{ $call->qty }}</div>
                        <div><b>Price:</b> Rs. {{ number_format($call->price, 2) }}</div>
                        <div><b>Total:</b> {{ $call->formatted_total }}</div>
                    </div>

                    <div class="col-md-6">
                        <div><b>Remark:</b> {{ $call->remark ?? '-' }}</div>
                        <div><b>Platform:</b> {{ $call->platform ?? '-' }}</div>
                        <div><b>Campaign:</b> {{ $call->campaign_name ?? '-' }}</div>
                        <div><b>Lead Status:</b> {{ $call->lead_status ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
