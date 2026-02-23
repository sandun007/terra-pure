@extends('wholesaler.layouts.app')
@push('title')
    {{ $product->name }} - Product Details
@endpush
@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        .product-hero {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .product-image-main {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: white;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .product-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .product-thumbnail:hover,
        .product-thumbnail.active {
            border-color: var(--primary);
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .product-price {
            margin-bottom: 1.5rem;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .original-price {
            font-size: 1.2rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 10px;
        }

        .wholesale-price {
            background: rgba(16, 185, 129, 0.1);
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        /* Quantity Controls */
        .quantity-section {
            margin-bottom: 1.5rem;
        }

        .quantity-control-detailed {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .quantity-btn-detailed {
            width: 48px;
            height: 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .quantity-btn-detailed:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .quantity-btn-detailed:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input-detailed {
            width: 100px;
            height: 48px;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .quantity-input-detailed:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            padding: 12px 15px;
            background: #f8fafc;
            border-radius: 10px;
        }

        .stock-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: {{ $product->qty > 50 ? '#10b981' : ($product->qty > 10 ? '#f59e0b' : '#ef4444') }};
            animation: pulse 2s infinite;
        }

        .stock-text {
            font-weight: 600;
            color: #475569;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .btn-action {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-add-to-cart:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-buy-now {
            background: linear-gradient(135deg, var(--secondary) 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-buy-now:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        .btn-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .product-tabs {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .tab-content {
            padding: 1.5rem 0;
        }

        .review-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .related-products {
            margin-top: 3rem;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.5rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .product-image-main {
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('wholesaler.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('wholesaler.shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="product-hero">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="product-image-main"
                             id="mainImage">
                    @else
                        <div class="product-image-main d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-box-open fa-4x text-muted"></i>
                        </div>
                    @endif

                    @if($product->additional_images)
                        <div class="product-thumbnails">
                            @foreach(json_decode($product->additional_images, true) as $index => $image)
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                     class="product-thumbnail {{ $index == 0 ? 'active' : '' }}"
                                     onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Title and Meta -->
                    <h1 class="product-title">{{ $product->name }}</h1>

                    <div class="product-meta">
                        <div class="product-rating">
                            @php
                                $avgRating = $product->average_rating ?? 0;
                                $fullStars = floor($avgRating);
                                $hasHalfStar = $avgRating - $fullStars >= 0.5;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <span class="ms-2">({{ $product->total_reviews ?? 0 }} reviews)</span>
                        </div>

                        <div class="product-sku">
                            <i class="fas fa-barcode me-2"></i>
                            <span class="text-muted">SKU: {{ $product->code }}</span>
                        </div>

                        <div class="product-category">
                            <i class="fas fa-tag me-2"></i>
                            <span class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="info-card">
                        <h5 class="mb-3 d-flex align-items-center">
                            <span class="material-icons-outlined me-2">payments</span>
                            Pricing Information
                        </h5>
                        <div class="price-card">
                            <div class="row">
                                <!-- Cost Price -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Cost Price</div>
                                        <div class="price-display" style="color: #6b7280;">
                                            Rs. {{ number_format($product->cost_price, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Selling Price -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Selling Price</div>
                                        <div class="price-display">
                                            Rs. {{ number_format($product->selling_price, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Final Price -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Final Price</div>
                                        <div class="price-display" style="color: #10b981;">
                                            Rs. {{ number_format($product->final_price, 2) }}
                                            @if($product->discount > 0)
                                                <span class="badge bg-success ms-2">-{{ $product->discount }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Pricing Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="text-muted">Packaging Cost:</span>
                                        <span class="fw-medium">Rs. {{ number_format($product->packaging_cost, 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="text-muted">Total Cost:</span>
                                        <span class="fw-medium">Rs. {{ number_format($product->total_cost, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Profit Calculation -->
                            @if($product->profit_amount > 0)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="text-muted small">Profit Amount</div>
                                                <div class="fw-bold text-success">
                                                    Rs. {{ number_format($product->profit_amount, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="text-muted small">Profit Margin</div>
                                                <div class="fw-bold {{ $product->profit_margin > 20 ? 'text-success' : ($product->profit_margin > 10 ? 'text-warning' : 'text-info') }}">
                                                    {{ number_format($product->profit_margin, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Updated Quantity Control Section -->
                    <div class="quantity-section">
                        <label class="fw-bold mb-2 d-block">Quantity:</label>
                        <div class="quantity-control-detailed">
                            <button class="quantity-btn-detailed decrement-qty-detailed"
                                    type="button"
                                    onclick="decrementQuantityDetailed()"
                                    disabled>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number"
                                   id="productQuantity"
                                   class="form-control text-center quantity-input-detailed"
                                   value="0"
                                   min="0"
                                   max="{{ $product->qty }}"
                                   data-price="{{ $product->final_price }}"
                                   oninput="updateQuantityDetailed(this.value)">
                            <button class="quantity-btn-detailed increment-qty-detailed"
                                    type="button"
                                    onclick="incrementQuantityDetailed()"
                                {{ $product->qty == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            @if($product->qty <= $product->min_stock_level)
                                <div class="alert alert-warning py-2 mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Low Stock: Only {{ $product->qty }} items left
                                </div>
                            @else
                                <div class="text-muted small">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    {{ $product->qty }} items available
                                </div>
                            @endif
                            <div class="text-muted small">
                                <i class="fas fa-warehouse me-2"></i>
                                Min Stock Level: {{ $product->min_stock_level }}
                            </div>
                        </div>
                    </div>
                    <!-- Total Price Display -->
                    <div class="total-price-display mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                            <span class="fw-bold">Total:</span>
                            <span class="fs-4 fw-bold text-primary" id="totalPriceDetailed">Rs. 0</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-action btn-add-to-cart"
                                onclick="addToCart({{ $product->id }})"
                                id="addToCartBtn"
                                disabled>
                            <i class="fas fa-cart-plus me-2"></i>
                            Add to Cart
                        </button>
                        <button class="btn-action btn-buy-now"
                                onclick="buyNow({{ $product->id }})"
                                id="buyNowBtn"
                                disabled>
                            <i class="fas fa-bolt me-2"></i>
                            Buy Now
                        </button>
                    </div>

                    <!-- Product Features -->
                    @if($product->features)
                        <div class="product-features mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-star me-2"></i>
                                Key Features
                            </h6>
                            <ul class="list-unstyled">
                                @foreach(explode(',', $product->features) as $feature)
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        {{ trim($feature) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="product-tabs">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                    <i class="fas fa-file-alt me-2"></i>
                    Description
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                    <i class="fas fa-list me-2"></i>
                    Specifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="fas fa-star me-2"></i>
                    Reviews ({{ $reviews->total() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="productTabContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description">
                <div class="product-description">
                    {!! $product->description !!}
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane fade" id="specifications">
                <div class="product-specifications">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th width="200">Brand</th>
                            <td>{{ $product->brand->name ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $product->category->name ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Weight</th>
                            <td>{{ $product->weight ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dimensions</th>
                            <td>{{ $product->dimensions ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Material</th>
                            <td>{{ $product->material ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer</th>
                            <td>{{ $product->manufacturer ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Country of Origin</th>
                            <td>{{ $product->country_of_origin ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Warranty</th>
                            <td>{{ $product->warranty ?? 'No warranty' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews">
                <!-- Add Review Form -->
                @auth('wholesaler')
                    <div class="add-review mb-4">
                        <h5 class="fw-bold mb-3">Add Your Review</h5>
                        <form action="{{ route('wholesaler.shop.product.review', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                        <label for="star{{ $i }}" title="{{ $i }} stars">
                                            <i class="far fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="review" rows="4" placeholder="Write your review here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Review
                            </button>
                        </form>
                    </div>
                @endauth

                <!-- Reviews List -->
                <div class="reviews-list">
                    <h5 class="fw-bold mb-3">Customer Reviews</h5>

                    <!-- Rating Summary -->
                    <div class="rating-summary mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="average-rating">
                                    <h1 class="display-4 fw-bold text-primary">{{ number_format($product->average_rating, 1) }}</h1>
                                    <div class="stars mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->average_rating))
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == ceil($product->average_rating) && fmod($product->average_rating, 1) >= 0.5)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">{{ $product->total_reviews }} reviews</p>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="rating-distribution">
                                    @for($rating = 5; $rating >= 1; $rating--)
                                        @php
                                            $ratingCount = $ratingDistribution->firstWhere('rating', $rating)->count ?? 0;
                                            $percentage = $product->total_reviews > 0 ? ($ratingCount / $product->total_reviews) * 100 : 0;
                                        @endphp
                                        <div class="rating-bar mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="rating-label me-2">{{ $rating }} star</span>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="rating-count ms-2">{{ $ratingCount }}</span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews -->
                    @forelse($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-user">
                                    <div class="user-avatar">
                                        @if($review->user->avatar)
                                            <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                                 alt="{{ $review->user->name }}"
                                                 class="rounded-circle"
                                                 width="40"
                                                 height="40">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <div class="stars small">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-muted small">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="review-content">
                                <p class="mb-0">{{ $review->review }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reviews yet</h5>
                            <p class="text-muted">Be the first to review this product!</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h3 class="fw-bold mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <div class="card product-card h-100">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                     class="card-img-top"
                                     alt="{{ $relatedProduct->name }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-box-open fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="{{ route('wholesaler.shop.product.show', $relatedProduct->id) }}"
                                       class="text-dark text-decoration-none">
                                        {{ Str::limit($relatedProduct->name, 40) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">
                                        Rs. {{ number_format($relatedProduct->display_price) }}
                                    </span>
                                    <button class="btn btn-sm btn-outline-primary"
                                            onclick="window.location.href='{{ route('wholesaler.shop.product.show', $relatedProduct->id) }}'">
                                        <i class="fas fa-eye me-1"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        // Product details quantity functions
        function updateQuantityDetailed(value) {
            const quantityInput = document.getElementById('productQuantity');
            const decrementBtn = document.querySelector('.decrement-qty-detailed');
            const incrementBtn = document.querySelector('.increment-qty-detailed');
            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyNowBtn = document.getElementById('buyNowBtn');
            const totalPriceElement = document.getElementById('totalPriceDetailed');

            let quantity = parseInt(value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;
            const unitPrice = parseFloat(quantityInput.dataset.price) || 0; // Use final_price

            // Validate input
            if (isNaN(quantity) || quantity < 0) quantity = 0;
            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                showNotification('warning', `Only ${maxQuantity} items available`);
            }

            // Update input value
            quantityInput.value = quantity;

            // Update button states
            decrementBtn.disabled = quantity <= 0;
            incrementBtn.disabled = quantity >= maxQuantity;

            // Update cart buttons
            addToCartBtn.disabled = quantity === 0;
            buyNowBtn.disabled = quantity === 0;

            // Calculate and update total price
            const totalPrice = quantity * unitPrice;
            totalPriceElement.textContent = 'Rs. ' + totalPrice.toLocaleString();
        }

        function incrementQuantityDetailed() {
            const quantityInput = document.getElementById('productQuantity');
            let currentValue = parseInt(quantityInput.value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            if (currentValue < maxQuantity) {
                updateQuantityDetailed(currentValue + 1);
            }
        }

        function decrementQuantityDetailed() {
            const quantityInput = document.getElementById('productQuantity');
            let currentValue = parseInt(quantityInput.value) || 0;

            if (currentValue > 0) {
                updateQuantityDetailed(currentValue - 1);
            }
        }

        // Add to cart function for product details
        async function addToCart(productId) {
            const quantityInput = document.getElementById('productQuantity');
            const quantity = parseInt(quantityInput.value) || 0;

            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            const addToCartBtn = document.getElementById('addToCartBtn');
            const originalText = addToCartBtn.innerHTML;

            // Show loading
            addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            addToCartBtn.disabled = true;

            try {
                const response = await fetch('{{ route("wholesaler.shop.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('success', data.message || 'Product added to cart successfully!');

                    // Reset quantity to 0 after successful add
                    updateQuantityDetailed(0);
                } else {
                    showNotification('error', data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = quantity === 0;
            }
        }

        // Buy now function
        async function buyNow(productId) {
            const quantityInput = document.getElementById('productQuantity');
            const quantity = parseInt(quantityInput.value) || 0;

            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            const buyNowBtn = document.getElementById('buyNowBtn');
            const originalText = buyNowBtn.innerHTML;

            // Show loading
            buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            buyNowBtn.disabled = true;

            try {
                const response = await fetch('{{ route("wholesaler.shop.buy.now") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showNotification('error', data.message || 'Failed to process buy now');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                buyNowBtn.innerHTML = originalText;
                buyNowBtn.disabled = quantity === 0;
            }
        }

        // Change main image
        function changeMainImage(src, element) {
            document.getElementById('mainImage').src = src;

            // Update active thumbnail
            document.querySelectorAll('.product-thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Rating stars styling
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize rating stars
            const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const rating = this.value;
                    const stars = this.parentElement.querySelectorAll('label i');

                    stars.forEach((star, index) => {
                        if (5 - index <= rating) {
                            star.classList.remove('far');
                            star.classList.add('fas');
                        } else {
                            star.classList.remove('fas');
                            star.classList.add('far');
                        }
                    });
                });
            });

            // Initialize quantity
            updateQuantityDetailed(0);
        });
    </script>
@endpush
