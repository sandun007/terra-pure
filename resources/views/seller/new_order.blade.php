@extends('seller.app')
@push('title')
    New Order
@endpush
@section('content')
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
            <div class="col-4">
                <label>Seller ID</label>
                <input type="text" class="form-control" value="SL00012" disabled>
            </div>
            <div class="col-4">
                <label>Order Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-4">
                <label>Social Media </label>
                <select class="form-control">
                    <option value="">Facebook</option>
                    <option value="">TikTok</option>
                    <option value="">Instagram</option>
                </select>
            </div>
        </div>
{{--        <section class="mt-5" style="border: 2px solid gray; border-radius: 15px;">--}}
            <div class="row mt-3">
                <div class="col-12">
                    <div>
                    <label>Upload Excel File</label>
                    <input type="file" class="form-control">
                    </div>
                </div>
            </div>
{{--        </section>--}}
    </div>
@endsection
@push('script')

@endpush
