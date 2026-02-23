@extends('wholesaler.layouts.app')
@push('title')
    Wholesaler Shop
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        :root {
            --main-theme-color: #667eea;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        .shop-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
        }

        .filter-sidebar {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 100%;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--danger-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 2;
        }

        .product-rating {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2;
        }

        .product-content {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-meta {
            display: flex;
            gap: 10px;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .meta-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .product-pricing {
            margin: 1rem 0;
        }

        .price-display {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--main-theme-color);
        }

        .price-original {
            font-size: 0.95rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .wholesale-price {
            color: var(--success-color);
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 5px;
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .stock-high {
            color: var(--success-color);
        }

        .stock-low {
            color: var(--danger-color);
        }

        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-add-cart {
            flex: 1;
            background: var(--main-theme-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-add-cart:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-buy-now {
            flex: 1;
            background: var(--success-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-buy-now:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-view-details {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-view-details:hover {
            background: var(--main-theme-color);
            color: white;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .filter-group {
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }

        .filter-option input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .price-range-inputs {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .price-input {
            flex: 1;
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }

        .btn-apply-filters {
            width: 100%;
            background: var(--main-theme-color);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 1rem;
        }

        .sort-dropdown {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .filter-sidebar {
                position: static;
                margin-bottom: 2rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="shop-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">Wholesaler Shop</h1>
                    <p class="mb-0">Discover quality products at wholesale prices</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('wholesaler.shop.cart') }}" class="btn btn-light position-relative">
                        <span class="material-icons-outlined me-2">shopping_cart</span>
                        Cart
                        <span class="cart-badge" id="cartCount">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <form action="{{ route('wholesaler.shop.index') }}" method="GET">
                        <!-- Categories Filter -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <span class="material-icons-outlined">category</span>
                                Categories
                            </h6>
                            @foreach($categories as $category)
                                <div class="filter-option">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                           id="cat{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label for="cat{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Brands Filter -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <span class="material-icons-outlined">branding_watermark</span>
                                Brands
                            </h6>
                            @foreach($brands as $brand)
                                <div class="filter-option">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                           id="brand{{ $brand->id }}"
                                        {{ request('brand') == $brand->id ? 'checked' : '' }}>
                                    <label for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <h6 class="filter-title">
                                <span class="material-icons-outlined">payments</span>
                                Price Range
                            </h6>
                            <div class="price-range-inputs">
                                <input type="number" name="min_price" class="price-input" placeholder="Min"
                                       value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="price-input" placeholder="Max"
                                       value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn-apply-filters">
                            <span class="material-icons-outlined me-2">filter_alt</span>
                            Apply Filters
                        </button>
                        <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Clear Filters
                        </a>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-0">{{ $products->total() }} Products Found</h5>
                    </div>
                    <div>
                        <select class="sort-dropdown" onchange="window.location.href=this.value">
                            <option value="{{ route('wholesaler.shop.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ route('wholesaler.shop.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}"
                                {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ route('wholesaler.shop.index', array_merge(request()->all(), ['sort' => 'price_high'])) }}"
                                {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ route('wholesaler.shop.index', array_merge(request()->all(), ['sort' => 'rating'])) }}"
                                {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="{{ route('wholesaler.shop.index', array_merge(request()->all(), ['sort' => 'popular'])) }}"
                                {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="product-image">
                                    @else
                                        <div class="product-image d-flex align-items-center justify-content-center">
                                            <span class="material-icons-outlined" style="font-size: 4rem; color: #94a3b8;">
                                                image
                                            </span>
                                        </div>
                                    @endif

                                    @if($product->qty < 10)
                                        <span class="product-badge">Low Stock</span>
                                    @endif

                                    @if($product->average_rating > 0)
                                        <div class="product-rating">
                                            <span class="material-icons-outlined" style="color: #f59e0b; font-size: 1rem;">
                                                star
                                            </span>
                                            {{ number_format($product->average_rating, 1) }}
                                            <span style="color: #94a3b8;">({{ $product->total_reviews }})</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="product-content">
                                    <h5 class="product-title">{{ $product->name }}</h5>

                                    <div class="product-meta">
                                        @if($product->category)
                                            <span class="meta-badge">
                                                <span class="material-icons-outlined" style="font-size: 0.9rem;">category</span>
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                        @if($product->brand)
                                            <span class="meta-badge">
                                                <span class="material-icons-outlined" style="font-size: 0.9rem;">branding_watermark</span>
                                                {{ $product->brand->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-pricing">
                                        <div class="price-display">
                                            Rs. {{ number_format($product->display_price, 2) }}
                                        </div>
                                        <div class="wholesale-price">
                                            <span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">
                                                store
                                            </span>
                                            Wholesale: Rs. {{ number_format($product->wholesale_price, 2) }}
                                        </div>
                                    </div>

                                    <div class="stock-info {{ $product->qty > 50 ? 'stock-high' : 'stock-low' }}">
                                        <span class="material-icons-outlined" style="font-size: 1rem;">
                                            {{ $product->qty > 50 ? 'check_circle' : 'warning' }}
                                        </span>
                                        {{ $product->qty }} in stock | Min Order: {{ $product->min_order_quantity }}
                                    </div>

                                    <div class="product-actions">
                                        <button class="btn-add-cart" onclick="addToCart({{ $product->id }}, {{ $product->min_order_quantity }})">
                                            <span class="material-icons-outlined">add_shopping_cart</span>
                                            Add to Cart
                                        </button>
                                        <button class="btn-buy-now" onclick="buyNow({{ $product->id }}, {{ $product->min_order_quantity }})">
                                            <span class="material-icons-outlined">shopping_bag</span>
                                            Buy Now
                                        </button>
                                        <a href="{{ route('wholesaler.shop.product.show', $product->id) }}" class="btn-view-details">
                                            <span class="material-icons-outlined">visibility</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <span class="material-icons-outlined" style="font-size: 5rem; color: #94a3b8;">
                                inventory_2
                            </span>
                            <h4 class="mt-3 text-muted">No Products Found</h4>
                            <p class="text-muted">Try adjusting your filters</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function addToCart(productId, minQty) {
            fetch('{{ route("wholesaler.shop.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: minQty
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cartCount').textContent = data.cartCount;
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding to cart');
                });
        }

        function buyNow(productId, minQty) {
            fetch('{{ route("wholesaler.shop.buy.now") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: minQty
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing request');
                });
        }
    </script>
@endpush
