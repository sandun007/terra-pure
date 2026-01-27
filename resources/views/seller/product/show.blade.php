<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $product->name ?? 'Product View' }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 30px;
        }

        .product-image-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 600px;
            border-right: 1px solid #e2e8f0;
        }

        .logo-container {
            width: 100%;
            margin-bottom: 30px;
        }

        .product-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .product-details-section {
            padding: 40px;
        }

        .product-code-badge {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .category-brand-badges {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .badge-custom {
            padding: 6px 14px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-category {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .badge-brand {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .description-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .description-title {
            font-weight: 600;
            color: #64748b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .description-text {
            color: #475569;
            line-height: 1.8;
            text-align: justify;
        }

        .price-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .price-display {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: #dc2626;
        }

        .original-price {
            font-size: 1.25rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .discount-badge {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .stock-in {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .stock-low {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .stock-out {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .qty-input {
            width: 100px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px;
        }

        .qty-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .total-amount {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 2px dashed #10b981;
        }

        .total-amount h3 {
            color: #10b981;
            font-weight: 800;
            font-size: 1.8rem;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-action {
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 12px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-buy-now {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-buy-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .features-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        .features-list li i {
            color: #10b981;
        }

        @media (max-width: 768px) {
            .product-image-section {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                min-height: 400px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .current-price {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#" onclick="window.history.back(); return false;">
            <i class="fas fa-arrow-left me-2"></i> Back to Shopping
        </a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="product-container">
                <div class="row g-0">
                    <!-- Left Column - Product Image -->
                    <div class="col-lg-6">
                        <div class="product-image-section">
                            <div class="logo-container">
                                <img src="{{asset('asset/img/wordmark.png')}}" alt="Logo" class="img-fluid" style="max-width: 150px;">
                            </div>
                            <div>
                                <img src="{{ $product->image_url ?? asset('asset/img/theme/item.png') }}"
                                     alt="{{ $product->name ?? 'Product' }}"
                                     class="product-image">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Details -->
                    <div class="col-lg-6">
                        <div class="product-details-section">
                            <!-- Product Code -->
                            <div>
                                <span class="product-code-badge">
                                    <i class="fas fa-barcode me-2"></i>
                                    {{ $product->code ?? 'PRD-001' }}
                                </span>
                            </div>

                            <!-- Product Title -->
                            <h1 class="product-title">
                                <i class="fas fa-box-open text-primary me-3"></i>
                                {{ $product->name ?? 'Product Name' }}
                            </h1>

                            <!-- Category and Brand Badges -->
                            @if(isset($product))
                                <div class="category-brand-badges">
                                    @if($product->categoryModel)
                                        <span class="badge-custom badge-category">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $product->categoryModel->name }}
                                        </span>
                                    @endif
                                    @if($product->brandModel)
                                        <span class="badge-custom badge-brand">
                                            <i class="fas fa-certificate me-1"></i>
                                            {{ $product->brandModel->name }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Description -->
                            <div class="description-section">
                                <h5 class="description-title">
                                    <i class="fas fa-align-left"></i>
                                    Description
                                </h5>
                                <p class="description-text">
                                    {{ $product->description ?? 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi aperiam harum modi quasi quisquam saepe sit vitae. Est, quibusdam quidem.' }}
                                </p>
                            </div>

                            <!-- Stock Status -->
                            @php
                                $qty = $product->qty ?? 0;
                                $minQty = $product->min_qty ?? 0;
                                $stockClass = $qty == 0 ? 'stock-out' : ($qty <= $minQty ? 'stock-low' : 'stock-in');
                                $stockText = $qty == 0 ? 'Out of Stock' : ($qty <= $minQty ? 'Low Stock - Hurry!' : 'In Stock');
                                $stockIcon = $qty == 0 ? 'fa-times-circle' : ($qty <= $minQty ? 'fa-exclamation-triangle' : 'fa-check-circle');
                            @endphp
                            <div class="stock-status {{ $stockClass }}">
                                <i class="fas {{ $stockIcon }}"></i>
                                <span>{{ $stockText }}</span>
                                @if($qty > 0)
                                    <span class="ms-2">({{ $qty }} available)</span>
                                @endif
                            </div>

                            <!-- Price Section -->
                            <div class="price-section">
                                <div class="price-display">
                                    @php
                                        $displayPrice = $product->display_price ?? 1500;
                                        $discount = $product->discount ?? 0;
                                        $finalPrice = $displayPrice - ($displayPrice * $discount / 100);
                                    @endphp
                                    <h2 class="current-price">
                                        <i class="fas fa-tag"></i>
                                        Rs.{{ number_format($finalPrice, 2) }}
                                    </h2>
                                    @if($discount > 0)
                                        <span class="original-price">Rs.{{ number_format($displayPrice, 2) }}</span>
                                        <span class="discount-badge">{{ $discount }}% OFF</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="quantity-selector">
                                <label class="fw-bold">Quantity:</label>
                                <input type="number" class="qty-input" id="qtyInput" value="1" min="1" max="{{ $qty }}" {{ $qty == 0 ? 'disabled' : '' }}>
                            </div>

                            <!-- Total Amount -->
                            <div class="total-amount">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Amount:</span>
                                    <h3 id="totalAmount">Rs.{{ number_format($finalPrice, 2) }}</h3>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button class="btn-action btn-add-cart" {{ $qty == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus fa-lg"></i>
                                    <span>Add To Cart</span>
                                </button>
                                <button class="btn-action btn-buy-now" {{ $qty == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-bolt fa-lg"></i>
                                    <span>Buy Now</span>
                                </button>
                            </div>

                            <!-- Features Section -->
                            @if(isset($product) && $product->features)
                                <div class="features-section">
                                    <h5 class="description-title">
                                        <i class="fas fa-star"></i>
                                        Key Features
                                    </h5>
                                    <ul class="features-list">
                                        @foreach(explode(',', $product->features) as $feature)
                                            <li>
                                                <i class="fas fa-check"></i>
                                                <span>{{ trim($feature) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('qtyInput');
        const totalAmountDisplay = document.getElementById('totalAmount');
        const unitPrice = {{ $finalPrice }};

        if (qtyInput) {
            qtyInput.addEventListener('input', function() {
                const qty = parseInt(this.value) || 1;
                const total = unitPrice * qty;
                totalAmountDisplay.textContent = 'Rs.' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            });
        }

        // Add to cart functionality
        document.querySelector('.btn-add-cart')?.addEventListener('click', function() {
            const qty = parseInt(qtyInput.value) || 1;
            alert(`Added ${qty} item(s) to cart!`);
            // Add your cart logic here
        });

        // Buy now functionality
        document.querySelector('.btn-buy-now')?.addEventListener('click', function() {
            const qty = parseInt(qtyInput.value) || 1;
            alert(`Proceeding to checkout with ${qty} item(s)!`);
            // Add your checkout logic here
        });
    });
</script>
</body>
</html>
