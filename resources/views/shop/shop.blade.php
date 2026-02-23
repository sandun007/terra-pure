<!--@extends('seller.layout.app')-->

<!--@push('title')-->
<!--    Shop-->
<!--@endpush-->

<!--@push('css')-->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papW82Cc9aU+8w0kGfAetPi6F4LoCS9pbxIQJv9EGozv3giINpSsyLMp1UIZTxUHLxtu2x2YxA7qZddIVTn3vA==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->

<!--    <style>-->
<!--        p {-->
<!--            padding: 0 !important;-->
<!--            margin: 0 !important;-->
<!--        }-->

<!--        @media (max-width: 1024px) {-->
<!--            #product_img {-->
<!--                height: 90px !important;-->
<!--            }-->
<!--        }-->

<!--        .product-card {-->
<!--            border-radius: 12px;-->
<!--            border: 1px solid #eee;-->
<!--            transition: transform 0.2s, box-shadow 0.2s;-->
<!--            overflow: hidden;-->
<!--            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);-->
<!--            position: relative;-->
<!--        }-->

<!--        .product-card:hover {-->
<!--            transform: scale(1.02);-->
<!--            box-shadow: 0 4px 12px rgb(117, 221, 94);-->
<!--        }-->

<!--        .product-img {-->
<!--            height: 100px;-->
<!--            object-fit: contain;-->
<!--            padding: 10px;-->
<!--            transition: transform 0.3s;-->
<!--        }-->

<!--        .product-name {-->
<!--            font-size: 14px;-->
<!--            font-weight: 500;-->
<!--            color: #333;-->
<!--            height: 20px;-->
<!--            overflow: hidden;-->
<!--            text-overflow: ellipsis;-->
<!--            display: -webkit-box;-->
<!--            -webkit-line-clamp: 2;-->
<!--            -webkit-box-orient: vertical;-->
<!--        }-->

<!--        .product-code {-->
<!--            font-size: 12px;-->
<!--            color: #888;-->
<!--        }-->

<!--        .product-price {-->
<!--            font-size: 16px;-->
<!--            color: #e02e24;-->
<!--            font-weight: bold;-->
<!--        }-->

<!--        .buy-btn {-->
<!--            font-size: 13px;-->
/*padding: 4px 0;*/
<!--        }-->

<!--        .discount-badge {-->
<!--            position: absolute;-->
<!--            top: 8px;-->
<!--            left: 8px;-->
<!--            background-color: #e53935;-->
<!--            color: white;-->
<!--            font-size: 12px;-->
<!--            padding: 2px 6px;-->
<!--            border-radius: 4px;-->
<!--            z-index: 2;-->
<!--        }-->

<!--        .product-rating {-->
<!--            margin: 4px 0;-->
<!--            font-size: 12px;-->
<!--        }-->
<!--    </style>-->
<!--@endpush-->

<!--@section('content')-->
<!--    <div class="">-->
<!--        <div class="row">-->
<!--            <div class="col-4 col-sm-4 col-md-8">-->
<!--                <h5>Shop</h5>-->
<!--            </div>-->
<!--            <div class="col-8 col-sm-8 col-md-4">-->
<!--                <input type="text" id="productSearch" class="form-control" placeholder="Search product by name or code...">-->
<!--            </div>-->
<!--        </div>-->
<!--        <hr>-->
<!--        <div class="row">-->
<!--            <div class="col-12">-->
<!--                <h4 class="mb-3">Best Selling Product</h4>-->
<!--            </div>-->
<!--            <div class="col-12">-->
<!--                <div class="row">-->
<!--                    @foreach($products as $product)-->
<!--                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">-->
<!--                            <div class="card product-card h-100">-->
<!--                        @if($product->discount > 0)-->
<!--                            <span class="discount-badge">{{ $product->discount }}%</span>-->
<!--                        @endif-->

<!-- Background image inside card-body -->
<!--                                <div class="card-body p-2 d-flex justify-content-center align-items-center"-->
<!--                                     style="background-image: url('{{ asset('storage/' . $product->img) }}');-->
<!--                                     background-size: cover;-->
<!--                                     background-position: center;-->
<!--                                     height: 100px;">-->
<!--                                </div>-->

<!--                                <div class="card-footer text-center bg-white border-top-0">-->
<!--                                    <p class="product-name">{{ $product->name }}</p>-->
<!--                                    <p class="product-code">{{ $product->code }}</p>-->
<!--                                    <div class="product-rating">-->
<!--                                        {{--                                        <i class="fas fa-star text-warning"></i>--}}-->
<!--                                        {{--                                        <i class="fas fa-star text-warning"></i>--}}-->
<!--                                        {{--                                        <i class="fas fa-star text-warning"></i>--}}-->
<!--                                        {{--                                        <i class="fas fa-star-half-alt text-warning"></i>--}}-->
<!--                                        <i class="far fa-star text-warning"></i>-->
<!--                                        <i class="far fa-star text-warning"></i>-->
<!--                                        <i class="far fa-star text-warning"></i>-->
<!--                                        <i class="far fa-star text-warning"></i>-->
<!--                                        <i class="far fa-star text-warning"></i>-->
<!--                                    </div>-->

<!--                                     <p class="product-price">Rs.{{ $product->display_price }}.00</p>-->
<!--                                    <button class="btn btn-dark btn-sm w-100 buy-btn mb-2">-->
<!--                                        <i class="fas fa-copy me-1"></i> Copy-->
<!--                                    </button>-->

<!--                                    <a href="{{route('seller.product.product_view',$product->id)}}" class="btn btn-success btn-sm w-100 buy-btn">-->
<!--                                        <i class="fas fa-cart-plus me-1"></i> View-->
<!--                                    </a>-->

<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    @endforeach-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--@endsection-->

<!--@push('script')-->
<!--    <script>-->
<!--        document.getElementById('productSearch').addEventListener('keyup', function () {-->
<!--            let keyword = this.value.toLowerCase();-->
<!--            let products = document.querySelectorAll('.product-card');-->

<!--            products.forEach(function (card) {-->
<!--                let name = card.querySelector('.product-name').textContent.toLowerCase();-->
<!--                let code = card.querySelector('.product-code').textContent.toLowerCase();-->

<!--                if (name.includes(keyword) || code.includes(keyword)) {-->
<!--                    card.parentElement.style.display = 'block';-->
<!--                } else {-->
<!--                    card.parentElement.style.display = 'none';-->
<!--                }-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!--@endpush-->
