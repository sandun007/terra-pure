@extends('wholesaler.layouts.app')
@push('title')
    {{ $shop->shop_name }} - Wholesale Shop
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <style>
        :root {
            --primary: #3a86ff;
            --secondary: #8338ec;
            --accent: #ff006e;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #06d6a0;
            --warning: #ffd166;
            --danger: #ef476f;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0v100l1000-100V0Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 100% 100px;
            background-position: bottom;
            background-repeat: no-repeat;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #e2e8f0;
            position: relative;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--primary);
        }

        .product-image-container {
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: var(--dark);
        }

        .action-btn:hover {
            transform: scale(1.1);
            background: var(--primary);
            color: white;
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }

        .original-price {
            font-size: 0.9rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .product-cta {
            display: flex;
            gap: 10px;
        }

        .btn-add-cart {
            flex: 1;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 134, 255, 0.3);
        }

        .btn-buy-now {
            background: var(--accent);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-buy-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 0, 110, 0.3);
        }

        .featured-slider {
            margin: 3rem 0;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 2rem;
            position: relative;
            padding-left: 20px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 40px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .filter-sidebar {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 20px;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .price-range {
            padding: 15px 0;
        }

        .range-slider {
            width: 100%;
            height: 5px;
            background: #e2e8f0;
            border-radius: 5px;
            margin: 20px 0;
            position: relative;
        }

        .range-slider .progress {
            height: 100%;
            left: 0%;
            right: 0%;
            position: absolute;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 5px;
        }

        .range-input input {
            position: absolute;
            width: 100%;
            height: 5px;
            top: -5px;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
        }

        .range-input input::-webkit-slider-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary);
            pointer-events: auto;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .pagination-custom {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 3rem;
        }

        .page-item.active .page-link {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .product-cta {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Welcome to {{ $shop->shop_name }}</h1>
                        <p>Discover premium quality products at wholesale prices. Browse our exclusive collection curated just for you.</p>
                        <div class="mt-4">
                            <a href="#products" class="btn btn-light btn-lg px-4 py-3 me-3">
                                <i class="fas fa-shopping-bag me-2"></i>Shop Now
                            </a>
                            @if(auth()->guard('wholesaler')->check())
                                <a href="{{ route('wholesaler.products.create') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                                    <i class="fas fa-plus-circle me-2"></i>Add Product
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="https://cdn.pixabay.com/photo/2016/11/23/14/45/shopping-1853543_1280.png"
                             alt="Online Shopping"
                             class="img-fluid"
                             style="max-height: 300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <!-- Search -->
                    <div class="search-box mb-4">
                        <form action="{{ route('wholesaler.shop.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search products..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="filter-group">
                        <h6 class="filter-title mb-3">
                            <i class="fas fa-list me-2"></i>
                            Categories
                        </h6>
                        <div class="filter-options">
                            <div class="filter-option mb-2">
                                <a href="{{ route('wholesaler.shop.index') }}"
                                   class="d-flex justify-content-between text-decoration-none {{ !request('category') ? 'text-primary' : 'text-dark' }}">
                                    <span>All Categories</span>
                                    <span class="badge bg-light text-dark">{{ $products->total() }}</span>
                                </a>
                            </div>
                            @foreach($categories as $category)
                                <div class="filter-option mb-2">
                                    <a href="{{ route('wholesaler.shop.index', ['category' => $category->id]) }}"
                                       class="d-flex justify-content-between text-decoration-none {{ request('category') == $category->id ? 'text-primary' : 'text-dark' }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <h6 class="filter-title mb-3">
                            <i class="fas fa-filter me-2"></i>
                            Price Range
                        </h6>
                        <div class="price-range">
                            <form action="{{ route('wholesaler.shop.index') }}" method="GET" id="priceFilterForm">
                                <div class="range-slider">
                                    <div class="progress"></div>
                                    <div class="range-input">
                                        <input type="range" class="range-min" min="0" max="100000" value="{{ request('min_price', 0) }}" step="100">
                                        <input type="range" class="range-max" min="0" max="100000" value="{{ request('max_price', 50000) }}" step="100">
                                    </div>
                                </div>
                                <div class="price-input mt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control input-min" name="min_price"
                                                   value="{{ request('min_price', 0) }}" placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control input-max" name="max_price"
                                                   value="{{ request('max_price', 50000) }}" placeholder="Max">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Apply Price</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Products Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title" id="products">All Products</h2>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="fas fa-box me-2"></i>
                            {{ $products->total() }} Products
                        </span>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4" id="productsGrid">
                    @forelse($products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="product-card">
                                <!-- Product Image -->
                                <div class="product-image-container">
                                    <a href="{{ route('wholesaler.shop.product.show', $product->id) }}">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image">
                                        @else
                                            <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-box-open fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </a>

                                    <!-- Quick Actions -->
                                    <div class="product-actions">
                                        <button class="action-btn" title="Quick View" onclick="quickView({{ $product->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn" title="Add to Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="product-info">
                                    <div class="product-category mb-2">
                                        <span class="badge bg-light text-dark">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </div>

                                    <h3 class="product-title">
                                        <a href="{{ route('wholesaler.shop.product.show', $product->id) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price -->
                                    <div class="product-price">
                                        <span class="current-price">
                                            Rs. {{ number_format($product->wholesale_price, 2) }}
                                        </span>
                                        @if($product->display_price > $product->wholesale_price)
                                            <span class="original-price">
                                                Rs. {{ number_format($product->display_price, 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock Status -->
                                    <div class="product-stock mb-3">
                                        <span class="badge {{ $product->qty > 50 ? 'bg-success' : ($product->qty > 10 ? 'bg-warning' : 'bg-danger') }}">
                                            <i class="fas fa-{{ $product->qty > 50 ? 'check-circle' : ($product->qty > 10 ? 'exclamation-circle' : 'times-circle') }} me-1"></i>
                                            {{ $product->qty > 50 ? 'In Stock' : ($product->qty > 10 ? 'Limited Stock' : 'Low Stock') }}
                                        </span>
                                    </div>

                                    <!-- Call to Action -->
                                    <div class="product-cta">
                                        <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                                            <i class="fas fa-cart-plus"></i>
                                            Add to Cart
                                        </button>
                                        <form action="{{ route('wholesaler.shop.buy.now') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="{{ $product->min_order_quantity }}">
                                            <button type="submit" class="btn-buy-now">
                                                <i class="fas fa-bolt"></i>
                                                Buy Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-box-open fa-4x"></i>
                                </div>
                                <h3 class="mb-3">No Products Found</h3>
                                <p class="text-muted mb-4">Start by adding your first product</p>
                                <a href="{{ route('wholesaler.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Add First Product
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <nav class="pagination-custom">
                        {{ $products->withQueryString()->links() }}
                    </nav>
                @endif
            </div>
        </div>

        <!-- Featured Products Section -->
        @if($featuredProducts->isNotEmpty())
            <section class="featured-slider">
                <h2 class="section-title">Featured Products</h2>
                <div class="swiper featuredSwiper">
                    <div class="swiper-wrapper">
                        @foreach($featuredProducts as $product)
                            <div class="swiper-slide">
                                <div class="product-card">
                                    <div class="product-image-container">
                                        <a href="{{ route('wholesaler.shop.product.show', $product->id) }}">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                     alt="{{ $product->name }}"
                                                     class="product-image">
                                            @else
                                                <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-title">
                                            <a href="{{ route('wholesaler.shop.product.show', $product->id) }}">
                                                {{ Str::limit($product->name, 50) }}
                                            </a>
                                        </h3>
                                        <div class="product-price">
                                            <span class="current-price">Rs. {{ number_format($product->wholesale_price, 2) }}</span>
                                        </div>
                                        <div class="product-cta">
                                            <button class="btn-add-cart w-100" onclick="addToCart({{ $product->id }})">
                                                <i class="fas fa-cart-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper
        var featuredSwiper = new Swiper('.featuredSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 }
            }
        });

        // Price Range Slider
        const rangeInput = document.querySelectorAll(".range-input input"),
            priceInput = document.querySelectorAll(".price-input input"),
            range = document.querySelector(".range-slider .progress");
        let priceGap = 1000;

        priceInput.forEach(input => {
            input.addEventListener("input", e => {
                let minPrice = parseInt(priceInput[0].value),
                    maxPrice = parseInt(priceInput[1].value);

                if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
                    if (e.target.className === "input-min") {
                        rangeInput[0].value = minPrice;
                        range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                    } else {
                        rangeInput[1].value = maxPrice;
                        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                    }
                }
            });
        });

        rangeInput.forEach(input => {
            input.addEventListener("input", e => {
                let minVal = parseInt(rangeInput[0].value),
                    maxVal = parseInt(rangeInput[1].value);

                if ((maxVal - minVal) < priceGap) {
                    if (e.target.className === "range-min") {
                        rangeInput[0].value = maxVal - priceGap;
                    } else {
                        rangeInput[1].value = minVal + priceGap;
                    }
                } else {
                    priceInput[0].value = minVal;
                    priceInput[1].value = maxVal;
                    range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                    range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
                }
            });
        });

        // Add to Cart Function
        function addToCart(productId) {
            const quantity = 1;
            $.ajax({
                url: '{{ route("wholesaler.shop.cart.add") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        updateCartCount(response.cart_count);
                        animateCart(productId);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Error adding to cart');
                }
            });
        }

        function updateCartCount(count) {
            const cartBadge = document.querySelector('.cart-count-badge');
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.classList.add('animate__animated', 'animate__bounceIn');
                setTimeout(() => {
                    cartBadge.classList.remove('animate__animated', 'animate__bounceIn');
                }, 500);
            }
        }

        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
        `;

            toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Load cart count on page load
        $(document).ready(function() {
            $.get('{{ route("wholesaler.shop.cart.count") }}', function(response) {
                updateCartCount(response.count);
            });
        });
    </script>
@endpush
