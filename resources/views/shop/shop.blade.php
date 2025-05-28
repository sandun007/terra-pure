@extends('admin.app')
@push('title')
    Shop
@endpush
@push('css')
    <style>
        p{
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Shop</h3>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Best Selling Product</h4>
            </div>
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                    @for ($i = 0; $i < 20; $i++) <!-- Example loop -->
                    <div class="card" style="width: 12.5%; min-width: 160px; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                        <div class="card-body d-flex justify-content-center">
                            <img src="{{ asset('asset/img/theme/item.png') }}" width="100px">
                        </div>
                        <div class="card-footer">
                            <p>Test Product {{ $i + 1 }}</p>
                            <p><span class="text-danger">Rs.500.00</span></p>
                            <button class="w-100 btn btn-success">Buy Now</button>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

    </div>

@endsection
@push('script')

@endpush
