@extends('wholesaler.layouts.app')
@push('title')
    {{ $product->name }} - {{ $shop->shop_name ?? 'Shop' }}
@endpush
@push('css')
    <style>
        :root {
            --primary-color: #3a86ff;
            --secondary-color: #8338ec;
            --accent-color: #ff006e;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }

        /* Product Gallery */
        .product-gallery {
            position: sticky;
            top: 20px;
        }

        .main-image-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 2rem;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .thumbnail-item {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .thumbnail-item.active {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Product Info */
        .product-info-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        .product-category-badge {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 6px 15px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .product-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .product-code {
            color: #64748b;
            font-family: 'Courier New', monospace;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .stars {
            color: #ffd700;
        }

        .rating-count {
            color: #64748b;
        }

        .price-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #bae6fd;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
        }

        .original-price {
            font-size: 1.5rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 10px;
        }

        .discount-badge {
            background: var(--accent-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 10px;
        }

        .price-breakdown {
            margin-top: 1rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .price-row:last-child {
            border-bottom: none;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Stock Info */
        .stock-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .stock-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stock-high {
            background: rgba(6, 214, 160, 0.1);
            color: #06d6a0;
        }

        .stock-low {
            background: rgba(239, 71, 111, 0.1);
            color: #ef476f;
        }

        .available-stock {
            color: #64748b;
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .qty-input {
            display: flex;
            align-items: center;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            width: 140px;
        }

        .qty-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--dark-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .qty-input input {
            width: 60px;
            height: 50px;
            border: none;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .min-order {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .btn-primary-lg {
            flex: 1;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary-lg:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(58, 134, 255, 0.3);
        }

        .btn-accent {
            background: var(--accent-color);
        }

        .btn-secondary-lg {
            background: white;
            color: var(--dark-color);
            border: 2px solid #e2e8f0;
            padding: 18px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-secondary-lg:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Product Details */
        .details-tabs {
            margin-top: 2rem;
        }

        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 15px 25px;
            border-radius: 10px 10px 0 0;
            margin-right: 5px;
        }

        .nav-tabs-custom .nav-link.active {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .tab-content {
            padding: 2rem;
            background: white;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .description-content {
            line-height: 1.8;
            color: #475569;
            white-space: pre-line;
        }

        .specs-table {
            width: 100%;
        }

        .spec-row {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .spec-row:nth-child(even) {
            background: #f8fafc;
        }

        .spec-label {
            width: 200px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .spec-value {
            flex: 1;
            color: #475569;
        }

        /* Related Products */
        .related-products {
            margin-top: 4rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 2rem;
            position: relative;
            padding-left: 15px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: transparent;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Share Section */
        .share-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .share-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .share-buttons {
            display: flex;
            gap: 10px;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .share-btn:hover {
            transform: translateY(-3px) scale(1.1);
        }

        .facebook { background: #3b5998; }
        .twitter { background: #1da1f2; }
        .whatsapp { background: #25d366; }
        .pinterest { background: #e60023; }

        /* Responsive */
        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary-lg,
            .btn-secondary-lg {
                width: 100%;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .product-info-card {
            animation: fadeIn 0.6s ease;
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('wholesaler.shop.index') }}">
                    <i class="fas fa-home me-1"></i>Shop
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('wholesaler.shop.index') }}?category={{ $product->category_id }}">
                    {{ $product->category->name ?? 'All Categories' }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <div class="main-image-container">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x400' }}"
                             alt="{{ $product->name }}"
                             class="main-image"
                             id="mainProductImage">
                    </div>

                    @if($product->image)
                        <div class="thumbnail-gallery">
                            <div class="thumbnail-item active" onclick="changeImage('{{ asset('storage/' . $product->image) }}')">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Main">
                            </div>
                            <!-- Add more thumbnails if you have multiple images -->
                        </div>
                    @endif

                    <!-- Product Tags -->
                    <div class="mt-3">
                        @if($product->created_at->diffInDays(now()) < 7)
                            <span class="badge bg-success me-2">NEW ARRIVAL</span>
                        @endif
                        @if($product->qty < 10)
                            <span class="badge bg-danger me-2">SELLING FAST</span>
                        @endif
                        @if($product->display_price > $product->wholesale_price)
                            <span class="badge bg-warning text-dark">SPECIAL OFFER</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 mb-4">
                <div class="product-info-card">
                    <!-- Category -->
                    <span class="product-category-badge">
                        <i class="fas fa-tag me-1"></i>
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>

                    <!-- Title -->
                    <h1 class="product-title">{{ $product->name }}</h1>

                    <!-- Code -->
                    <div class="product-code">
                        <i class="fas fa-barcode"></i>
                        Product Code: <strong>{{ $product->code }}</strong>
                        @if($product->barcode)
                            | Barcode: <strong>{{ $product->barcode }}</strong>
                        @endif
                    </div>

                    <!-- Rating -->
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= 4 ? ' text-warning' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-count">(4.2 • 128 Reviews)</span>
                        <span class="text-success ms-3">
                            <i class="fas fa-check-circle me-1"></i>Verified Wholesaler
                        </span>
                    </div>

                    <!-- Price Section -->
                    <div class="price-section">
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="current-price">Rs. {{ number_format($product->wholesale_price, 2) }}</span>

                            @if($product->display_price > $product->wholesale_price)
                                <span class="original-price">Rs. {{ number_format($product->display_price, 2) }}</span>
                                @php
                                    $discount = (($product->display_price - $product->wholesale_price) / $product->display_price) * 100;
                                @endphp
                                <span class="discount-badge">SAVE {{ round($discount) }}%</span>
                            @endif
                        </div>

                        <div class="price-breakdown mt-3">
                            <div class="price-row">
                                <span>Base Price</span>
                                <span>Rs. {{ number_format($product->wholesale_price, 2) }}</span>
                            </div>
                            <div class="price-row">
                                <span>Minimum Order</span>
                                <span>{{ $product->min_order_quantity }} units</span>
                            </div>
                            <div class="price-row">
                                <span>Estimated Delivery</span>
                                <span>2-5 Business Days</span>
                            </div>
                            <div class="price-row">
                                <span>Total (for {{ $product->min_order_quantity }} units)</span>
                                <span class="text-primary fw-bold">
                                    Rs. {{ number_format($product->wholesale_price * $product->min_order_quantity, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info">
                        <span class="stock-badge {{ $product->qty > 50 ? 'stock-high' : 'stock-low' }}">
                            <i class="fas fa-{{ $product->qty > 50 ? 'check-circle' : 'exclamation-circle' }}"></i>
                            {{ $product->qty > 50 ? 'In Stock' : 'Limited Stock' }}
                        </span>
                        <span class="available-stock">
                            Available: <strong>{{ $product->qty }}</strong> units
                        </span>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="quantity-selector">
                        <div class="qty-input">
                            <button class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number"
                                   id="quantityInput"
                                   value="{{ $product->min_order_quantity }}"
                                   min="{{ $product->min_order_quantity }}"
                                   max="{{ $product->qty }}">
                            <button class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                        <div class="min-order">
                            Minimum Order: {{ $product->min_order_quantity }} units
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-primary-lg" onclick="addToCart()">
                            <i class="fas fa-cart-plus"></i>
                            Add to Cart
                        </button>
                        <button class="btn-primary-lg btn-accent" onclick="buyNow()">
                            <i class="fas fa-bolt"></i>
                            Buy Now
                        </button>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-secondary-lg" onclick="addToWishlist()">
                            <i class="fas fa-heart"></i>
                            Add to Wishlist
                        </button>
                        <button class="btn-secondary-lg" onclick="compareProduct()">
                            <i class="fas fa-exchange-alt"></i>
                            Compare
                        </button>
                    </div>

                    <!-- Share Section -->
                    <div class="share-section">
                        <span class="share-label">Share this product:</span>
                        <div class="share-buttons">
                            <button class="share-btn facebook" onclick="shareOnFacebook()">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="share-btn twitter" onclick="shareOnTwitter()">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="share-btn whatsapp" onclick="shareOnWhatsApp()">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="share-btn pinterest" onclick="shareOnPinterest()">
                                <i class="fab fa-pinterest-p"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Guarantee -->
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-2x text-primary"></i>
                                <div class="small mt-1">Quality Guarantee</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-truck fa-2x text-success"></i>
                                <div class="small mt-1">Free Shipping*</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-undo fa-2x text-info"></i>
                                <div class="small mt-1">30-Day Returns</div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-headset fa-2x text-warning"></i>
                                <div class="small mt-1">24/7 Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="details-tabs">
                    <ul class="nav nav-tabs nav-tabs-custom" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description">
                                <i class="fas fa-file-alt me-2"></i>Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications">
                                <i class="fas fa-list-alt me-2"></i>Specifications
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features">
                                <i class="fas fa-star me-2"></i>Features
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews">
                                <i class="fas fa-comment-alt me-2"></i>Reviews (128)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping">
                                <i class="fas fa-shipping-fast me-2"></i>Shipping Info
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description">
                            <div class="description-content">
                                {{ $product->description ?? 'No description available for this product.' }}
                            </div>

                            @if($product->features)
                                <div class="mt-4">
                                    <h5>Key Highlights:</h5>
                                    <ul class="list-unstyled">
                                        @php
                                            $features = array_filter(array_map('trim', explode(',', $product->features)));
                                        @endphp
                                        @foreach($features as $feature)
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" id="specifications">
                            <div class="specs-table">
                                <div class="spec-row">
                                    <div class="spec-label">Product Code</div>
                                    <div class="spec-value">{{ $product->code }}</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Brand</div>
                                    <div class="spec-value">{{ $product->brand->name ?? 'N/A' }}</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Category</div>
                                    <div class="spec-value">{{ $product->category->name ?? 'N/A' }}</div>
                                </div>
                                @if($product->weight)
                                    <div class="spec-row">
                                        <div class="spec-label">Weight</div>
                                        <div class="spec-value">{{ $product->weight }} kg</div>
                                    </div>
                                @endif
                                @if($product->dimensions)
                                    <div class="spec-row">
                                        <div class="spec-label">Dimensions</div>
                                        <div class="spec-value">{{ $product->dimensions }}</div>
                                    </div>
                                @endif
                                <div class="spec-row">
                                    <div class="spec-label">Minimum Order</div>
                                    <div class="spec-value">{{ $product->min_order_quantity }} units</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Current Stock</div>
                                    <div class="spec-value">{{ $product->qty }} units</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Wholesale Price</div>
                                    <div class="spec-value">Rs. {{ number_format($product->wholesale_price, 2) }}</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Display Price</div>
                                    <div class="spec-value">Rs. {{ number_format($product->display_price, 2) }}</div>
                                </div>
                                <div class="spec-row">
                                    <div class="spec-label">Status</div>
                                    <div class="spec-value">
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features Tab -->
                        <div class="tab-pane fade" id="features">
                            @if($product->features)
                                <div class="row">
                                    @php
                                        $features = array_filter(array_map('trim', explode(',', $product->features)));
                                    @endphp
                                    @foreach($features as $feature)
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                                <span>{{ $feature }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No features specified for this product.</p>
                            @endif
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews">
                            <div class="text-center py-5">
                                <i class="fas fa-comment-alt fa-3x text-muted mb-3"></i>
                                <h4>No Reviews Yet</h4>
                                <p class="text-muted">Be the first to review this product!</p>
                                <button class="btn btn-primary">Write a Review</button>
                            </div>
                        </div>

                        <!-- Shipping Tab -->
                        <div class="tab-pane fade" id="shipping">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Shipping Information</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-truck text-primary me-2"></i>
                                            Free shipping on orders over Rs. 10,000
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-clock text-warning me-2"></i>
                                            Processing time: 1-2 business days
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-globe text-success me-2"></i>
                                            Ships to all major cities
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-box text-info me-2"></i>
                                            Estimated delivery: 2-5 business days
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Return Policy</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-undo text-danger me-2"></i>
                                            30-day return policy
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Free returns for defective items
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-tag text-warning me-2"></i>
                                            Original tags must be attached
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-calendar-alt text-info me-2"></i>
                                            Refund processed within 7 business days
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="row related-products">
                <div class="col-12">
                    <h3 class="section-title">Related Products</h3>
                    <div class="row g-4">
                        @foreach($relatedProducts as $related)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="product-card">
                                    <div class="product-image-container">
                                        <a href="{{ route('wholesaler.shop.product.show', $related->id) }}">
                                            @if($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}"
                                                     alt="{{ $related->name }}"
                                                     class="product-image">
                                            @else
                                                <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-box-open fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h5 class="product-title">
                                            <a href="{{ route('wholesaler.shop.product.show', $related->id) }}">
                                                {{ Str::limit($related->name, 40) }}
                                            </a>
                                        </h5>
                                        <div class="product-price">
                                            <span class="current-price">Rs. {{ number_format($related->wholesale_price, 2) }}</span>
                                        </div>
                                        <div class="product-cta">
                                            <button class="btn-add-cart w-100" onclick="addToCart({{ $related->id }})">
                                                <i class="fas fa-cart-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        // Quantity Controls
        function increaseQty() {
            const input = document.getElementById('quantityInput');
            const max = parseInt(input.max);
            const min = parseInt(input.min);
            let value = parseInt(input.value);

            if (value < max) {
                value++;
                input.value = value;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantityInput');
            const max = parseInt(input.max);
            const min = parseInt(input.min);
            let value = parseInt(input.value);

            if (value > min) {
                value--;
                input.value = value;
            }
        }

        // Validate quantity input
        document.getElementById('quantityInput').addEventListener('change', function() {
            const max = parseInt(this.max);
            const min = parseInt(this.min);
            let value = parseInt(this.value);

            if (isNaN(value) || value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
                showToast('error', `Maximum available quantity is ${max}`);
            }
        });

        // Add to Cart Function
        function addToCart() {
            const productId = {{ $product->id }};
            const quantity = parseInt(document.getElementById('quantityInput').value);

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

        // Buy Now Function
        function buyNow() {
            addToCart();
            setTimeout(() => {
                window.location.href = '{{ route("wholesaler.shop.checkout") }}';
            }, 500);
        }

        // Wishlist Function
        function addToWishlist() {
            showToast('info', 'Added to wishlist!');
        }

        // Compare Function
        function compareProduct() {
            showToast('info', 'Product added to comparison list');
        }

        // Share Functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function shareOnTwitter() {
            const text = encodeURIComponent('Check out this product: {{ $product->name }}');
            const url = encodeURIComponent(window.location.href);
            window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
        }

        function shareOnWhatsApp() {
            const text = encodeURIComponent('Check out this product: {{ $product->name }} - ' + window.location.href);
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }

        function shareOnPinterest() {
            const url = encodeURIComponent(window.location.href);
            const media = encodeURIComponent('{{ $product->image ? asset("storage/" . $product->image) : "" }}');
            const description = encodeURIComponent('{{ $product->name }}');
            window.open(`https://pinterest.com/pin/create/button/?url=${url}&media=${media}&description=${description}`, '_blank');
        }

        // Image Gallery
        function changeImage(src) {
            document.getElementById('mainProductImage').src = src;
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.thumbnail-item').classList.add('active');
        }

        // Toast Notification
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

        // Cart Animation
        function animateCart(productId) {
            const addBtn = document.querySelector('.btn-primary-lg');
            const cartIcon = document.querySelector('.fa-shopping-cart');

            if (!addBtn || !cartIcon) return;

            const btnRect = addBtn.getBoundingClientRect();
            const cartRect = cartIcon.getBoundingClientRect();

            const flyItem = document.createElement('div');
            flyItem.style.cssText = `
                position: fixed;
                width: 30px;
                height: 30px;
                background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
                border-radius: 50%;
                z-index: 9999;
                left: ${btnRect.left + btnRect.width/2}px;
                top: ${btnRect.top + btnRect.height/2}px;
                pointer-events: none;
                transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 14px;
            `;
            flyItem.innerHTML = '+1';

            document.body.appendChild(flyItem);

            setTimeout(() => {
                flyItem.style.left = `${cartRect.left}px`;
                flyItem.style.top = `${cartRect.top}px`;
                flyItem.style.transform = 'scale(0.1)';
            }, 10);

            setTimeout(() => {
                document.body.removeChild(flyItem);
                cartIcon.classList.add('animate__animated', 'animate__tada');
                setTimeout(() => {
                    cartIcon.classList.remove('animate__animated', 'animate__tada');
                }, 1000);
            }, 800);
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

        // Initialize on page load
        $(document).ready(function() {
            // Load cart count
            $.get('{{ route("wholesaler.shop.cart.count") }}', function(response) {
                updateCartCount(response.count);
            });

            // Initialize tab functionality
            const triggerTabList = document.querySelectorAll('#productTab button');
            triggerTabList.forEach(triggerEl => {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', event => {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
    </script>
@endpush
