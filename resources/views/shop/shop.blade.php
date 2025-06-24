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
                    @foreach($products as $product)
                    <div class="card" style="width: 12.5%; min-width: 160px; border-top-right-radius: 15px; border-top-left-radius: 15px;">
                        <div class="card-body d-flex justify-content-center">
                            <img src="{{ asset('storage/' . $product->img) }}" width="200px">
                        </div>
                        <div class="card-footer">
                            <p class="text-dark">{{$product->name}}</p>
                            <p class="text-secondary">{{$product->code}}</p>
                            <p><span class="text-danger">Rs.500.00</span></p>
                            <button class="w-100 btn btn-success">Buy Now</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

@endsection
@push('script')

@endpush
