@extends('seller.layout.app')
@push('title')
    New Order
@endpush
@section('content')
    @if($import == null)
        <div class="alert alert-warning">
            Not Available deposit
        </div>
    @endif
    <div class="container mt-4">
        <div class="row g-3">
            <!-- Card 1: Available Balance -->
            <div class="col-md-4">
                <div class="card text-white bg-success shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-wallet fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title mb-1 text-white">Available Balance</h6>
                            <h5 class="card-text text-white">{{ $user->cal_deposit }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Last Updated Cost -->
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-chart-line fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title mb-1 text-white">Last Updated Cost</h6>
                            <h5 class="card-text text-white">{{ $user->cost }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Last Upload Leads -->
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-user-plus fa-2x me-3"></i>
                        <div>
                            <h6 class="card-title mb-1 text-white">Last Upload Leads</h6>
                            <h5 class="card-text text-white">{{ $user->last_row_count ?? 'N/A' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('seller.order.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="container mt-3">
            <div class="row mt-5 mb-5">
                <div class="col-6">
                    <h3>New Order</h3>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <h3>Seller Name</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4">
                    <label>File No</label>
                    <input type="text" name="order_id" class="form-control" value="{{$code}}" disabled>
                </div>
                <div class="col-12 col-sm-12 col-md-4">
                    <label>Seller ID</label>
                    <input type="text" name="seller_id" class="form-control" value="{{auth()->user()->code}}" disabled>
                </div>

                <div class="col-12 col-sm-12 col-md-4">
                    <label>Order Date</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div class="col-12 col-sm-12 col-md-4">
                    <label>Social Media </label>
                    <select class="form-control" name="platform">
                        <option value="">Facebook</option>
                        <option value="">TikTok</option>
                        <option value="">Instagram</option>
                    </select>
                </div>

                {{--        <section class="mt-5" style="border: 2px solid gray; border-radius: 15px;">--}}

                <div class="col-12 col-sm-12 col-md-8">
                    <div>
                        <label>Upload Excel File</label>
                        <input type="file" name="excel" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-success">Upload Order</button>
                </div>
            </div>
            {{--        </section>--}}
        </div>
    </form>
@endsection
@push('script')

@endpush
