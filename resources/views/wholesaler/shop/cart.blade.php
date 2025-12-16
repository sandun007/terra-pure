@extends('wholesaler.layouts.app')
@push('title')
    Shopping Cart
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-theme-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --light-text: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cart-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cart-container:hover {
            box-shadow: var(--hover-shadow);
        }

        .cart-item {
            border-bottom: 1px solid var(--border-color);
            padding: 1.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .cart-item:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
            transform: translateX(5px);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .product-image-container {
            position: relative;
            flex-shrink: 0;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            padding: 8px;
            background: white;
            transition: all 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
            border-color: var(--main-theme-color);
        }

        .product-info {
            flex: 1;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-input-group {
            display: flex;
            align-items: center;
            background: var(--light-bg);
            border-radius: 12px;
            padding: 4px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .quantity-input-group:focus-within {
            border-color: var(--main-theme-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: white;
            color: var(--dark-text);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .qty-btn:hover:not(:disabled) {
            background: var(--main-theme-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 80px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-text);
            outline: none;
        }

        .remove-btn {
            color: var(--danger-color);
            border: none;
            background: none;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            transform: rotate(90deg);
        }

        .summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 100px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px dashed var(--border-color);
            transition: all 0.3s ease;
        }

        .summary-row:hover {
            padding-left: 10px;
            padding-right: 10px;
        }

        .total-row {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--main-theme-color);
            border-top: 2px solid var(--border-color);
            padding-top: 20px;
            margin-top: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            margin-left: -2rem;
            margin-right: -2rem;
            padding-left: 2rem;
            padding-right: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            border: 2px solid var(--border-color);
            padding: 16px 32px;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
        }

        .btn-outline:hover {
            background: var(--light-bg);
            border-color: var(--main-theme-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .empty-cart {
            text-align: center;
            padding: 6rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .empty-cart-icon {
            font-size: 6rem;
            color: #cbd5e1;
            margin-bottom: 2rem;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .price-badge {
            background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-top: 1rem;
        }

        .payment-method {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            transform: translateY(-2px);
            border-color: var(--main-theme-color);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--main-theme-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .cart-animation {
            animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-header-actions {
            display: flex;
            gap: 12px;
        }

        .cart-count-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .product-image {
                width: 100px;
                height: 100px;
            }

            .summary-card {
                position: static;
                margin-top: 2rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-2">Shopping Cart</h2>
                <p class="mb-0 opacity-90">
                    <span class="material-icons-round align-middle me-2">shopping_cart</span>
                    Review your items and proceed to checkout
                </p>
            </div>
            <div class="cart-header-actions">
                <div class="cart-count-badge">
                    <span class="material-icons-round me-1" style="font-size: 16px;">inventory_2</span>
                    {{ count($cartItems ?? []) }} Items
                </div>
                <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-light">
                    <span class="material-icons-round me-2">arrow_back</span>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>

    @if(empty($cartItems))
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <span class="material-icons-round">shopping_cart_off</span>
            </div>
            <h4 class="text-muted mb-3">Your cart is empty</h4>
            <p class="text-muted mb-4">Add some products to your cart to see them here</p>
            <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-primary">
                <span class="material-icons-round me-2">storefront</span>
                Browse Products
            </a>
        </div>
    @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="cart-container cart-animation">
                    @foreach($cartItems as $item)
                        <div class="cart-item" id="cart-item-{{ $item['product']->id }}">
                            <div class="loading-overlay" id="loading-{{ $item['product']->id }}">
                                <div class="loading-spinner"></div>
                            </div>

                            <div class="product-image-container">
                                @if($item['product']->image)
                                    <img src="{{ asset('storage/' . $item['product']->image) }}"
                                         alt="{{ $item['product']->name }}"
                                         class="product-image"
                                         loading="lazy">
                                @else
                                    <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                        <span class="material-icons-round text-muted" style="font-size: 48px;">
                                            inventory_2
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="product-info">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ route('wholesaler.shop.product.show', $item['product']->id) }}"
                                               class="text-dark text-decoration-none">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            <span class="material-icons-round" style="font-size: 16px;">qr_code_2</span>
                                            SKU: {{ $item['product']->code }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold h5 mb-1 text-primary">
                                            Rs. {{ number_format($item['total_price'], 2) }}
                                        </div>
                                        <div class="small text-muted">
                                            Rs. {{ number_format($item['product']->display_price, 2) }} × {{ $item['quantity'] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($item['product']->wholesale_price < $item['product']->display_price)
                                            <span class="price-badge">
                                                <span class="material-icons-round" style="font-size: 16px;">storefront</span>
                                                Wholesale Price: Rs. {{ number_format($item['product']->wholesale_price, 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stock-badge">
                                            <span class="material-icons-round" style="font-size: 16px;">inventory</span>
                                            {{ $item['product']->qty }} in stock
                                        </div>

                                        <div class="quantity-control">
                                            <div class="quantity-input-group">
                                                <button class="qty-btn decrement-qty"
                                                        data-product-id="{{ $item['product']->id }}"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                    <span class="material-icons-round">remove</span>
                                                </button>
                                                <input type="number"
                                                       class="quantity-input"
                                                       value="{{ $item['quantity'] }}"
                                                       min="1"
                                                       max="{{ $item['product']->qty }}"
                                                       data-product-id="{{ $item['product']->id }}"
                                                       id="qty-{{ $item['product']->id }}">
                                                <button class="qty-btn increment-qty"
                                                        data-product-id="{{ $item['product']->id }}"
                                                    {{ $item['quantity'] >= $item['product']->qty ? 'disabled' : '' }}>
                                                    <span class="material-icons-round">add</span>
                                                </button>
                                            </div>
                                            <button class="remove-btn remove-item"
                                                    data-product-id="{{ $item['product']->id }}"
                                                    title="Remove item">
                                                <span class="material-icons-round">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline" id="clearCartBtn">
                        <span class="material-icons-round me-2">delete_sweep</span>
                        Clear Cart
                    </button>
                    <div class="d-flex gap-3">
                        <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-outline">
                            <span class="material-icons-round me-2">add_shopping_cart</span>
                            Add More Items
                        </a>
                        <button class="btn btn-outline" id="saveCartBtn">
                            <span class="material-icons-round me-2">bookmark</span>
                            Save for Later
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="fw-bold mb-4">Order Summary</h5>

                    <div class="summary-row">
                        <span>Items ({{ count($cartItems) }})</span>
                        <span id="subtotalAmount">Rs. {{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span id="shippingAmount">Rs. 0.00</span>
                    </div>

                    <div class="summary-row">
                        <span>Tax (15%)</span>
                        <span id="taxAmount">Rs. {{ number_format($subtotal * 0.15, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Discount</span>
                        <span id="discountAmount" class="text-success">Rs. 0.00</span>
                    </div>

                    <div class="summary-row total-row">
                        <span>Total Amount</span>
                        <span id="totalAmount">Rs. {{ number_format($subtotal * 1.15, 2) }}</span>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary w-100 mb-3" id="checkoutBtn">
                            <span class="material-icons-round me-2">lock</span>
                            Proceed to Secure Checkout
                        </button>

                        <p class="text-muted small text-center mb-4">
                            <span class="material-icons-round align-middle me-1" style="font-size: 16px;">
                                verified
                            </span>
                            Secure checkout · 100% satisfaction guarantee
                        </p>

                        <!-- Payment Methods -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Accepted Payment Methods</h6>
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <span class="material-icons-round text-primary">credit_card</span>
                                </div>
                                <div class="payment-method">
                                    <span class="material-icons-round text-success">account_balance</span>
                                </div>
                                <div class="payment-method">
                                    <span class="material-icons-round text-warning">payments</span>
                                </div>
                                <div class="payment-method">
                                    <span class="material-icons-round text-info">qr_code_scanner</span>
                                </div>
                                <div class="payment-method">
                                    <span class="material-icons-round text-danger">currency_exchange</span>
                                </div>
                            </div>
                        </div>

                        <!-- Secure Badges -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="row text-center g-2">
                                <div class="col-6">
                                    <div class="text-muted small">
                                        <span class="material-icons-round align-middle me-1" style="font-size: 16px;">
                                            shield
                                        </span>
                                        SSL Secure
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small">
                                        <span class="material-icons-round align-middle me-1" style="font-size: 16px;">
                                            local_shipping
                                        </span>
                                        Free Shipping
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize cart animation
            initCartAnimations();

            // Initialize event listeners
            initEventListeners();

            // Function to initialize cart animations
            function initCartAnimations() {
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach((item, index) => {
                    item.style.animationDelay = `${index * 0.1}s`;
                });
            }

            // Function to initialize all event listeners
            function initEventListeners() {
                // Quantity controls
                document.querySelectorAll('.qty-btn').forEach(btn => {
                    btn.addEventListener('click', handleQuantityChange);
                });

                // Quantity input changes
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', handleQuantityInputChange);
                    input.addEventListener('blur', handleQuantityInputBlur);
                });

                // Remove item buttons
                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.addEventListener('click', handleRemoveItem);
                });

                // Clear cart button
                const clearCartBtn = document.getElementById('clearCartBtn');
                if (clearCartBtn) {
                    clearCartBtn.addEventListener('click', handleClearCart);
                }

                // Checkout button
                const checkoutBtn = document.getElementById('checkoutBtn');
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', handleCheckout);
                }

                // Save cart button
                const saveCartBtn = document.getElementById('saveCartBtn');
                if (saveCartBtn) {
                    saveCartBtn.addEventListener('click', handleSaveCart);
                }
            }

            // Handle quantity button clicks
            async function handleQuantityChange(e) {
                const btn = e.currentTarget;
                const productId = btn.dataset.productId;
                const action = btn.classList.contains('increment-qty') ? 'increment' : 'decrement';

                await updateQuantity(productId, action);
            }

            // Handle quantity input changes
            async function handleQuantityInputChange(e) {
                const input = e.currentTarget;
                const productId = input.dataset.productId;
                const value = parseInt(input.value);

                if (!isNaN(value)) {
                    await updateQuantity(productId, 'set', value);
                }
            }

            // Handle quantity input blur for validation
            function handleQuantityInputBlur(e) {
                const input = e.currentTarget;
                const min = parseInt(input.min);
                const max = parseInt(input.max);
                let value = parseInt(input.value);

                if (value < min) {
                    input.value = min;
                } else if (value > max) {
                    input.value = max;
                }
            }

            // Handle remove item
            async function handleRemoveItem(e) {
                const btn = e.currentTarget;
                const productId = btn.dataset.productId;

                Swal.fire({
                    title: 'Remove Item',
                    text: 'Are you sure you want to remove this item from your cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline'
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await removeItem(productId);
                    }
                });
            }

            // Handle clear cart
            async function handleClearCart() {
                Swal.fire({
                    title: 'Clear Cart',
                    text: 'Are you sure you want to remove all items from your cart?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clear cart',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline'
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await clearCart();
                    }
                });
            }

            // Handle checkout
            function handleCheckout() {
                window.location.href = '{{ route("wholesaler.shop.checkout") }}';
            }

            // Handle save cart
            async function handleSaveCart() {
                try {
                    {{--const response = await fetch('{{ route("wholesaler.shop.cart.save") }}', {--}}
                    {{--    method: 'POST',--}}
                    {{--    headers: {--}}
                    {{--        'Content-Type': 'application/json',--}}
                    {{--        'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
                    {{--    }--}}
                    // });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Cart saved successfully!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        showError('Failed to save cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('An error occurred while saving the cart');
                }
            }

            // Update quantity function
            async function updateQuantity(productId, action, value = null) {
                showLoading(productId, true);

                try {
                    const input = document.getElementById(`qty-${productId}`);
                    const currentQty = parseInt(input.value);
                    const maxQty = parseInt(input.max);
                    const minQty = parseInt(input.min);
                    let newQuantity;

                    // Determine new quantity
                    if (action === 'increment') {
                        newQuantity = currentQty + 1;
                        if (newQuantity > maxQty) {
                            showWarning(`Only ${maxQty} items available in stock`);
                            return;
                        }
                    } else if (action === 'decrement') {
                        newQuantity = currentQty - 1;
                        if (newQuantity < minQty) {
                            return; // Minimum quantity reached
                        }
                    } else if (action === 'set' && value !== null) {
                        newQuantity = parseInt(value);
                        if (newQuantity > maxQty) {
                            showWarning(`Only ${maxQty} items available in stock`);
                            newQuantity = maxQty;
                        } else if (newQuantity < minQty) {
                            newQuantity = minQty;
                        }
                    }

                    // Send update request
                    const response = await fetch('{{ route("wholesaler.shop.cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: newQuantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update UI
                        input.value = newQuantity;
                        updateCartItemUI(productId, newQuantity, data);
                        updateCartSummary(data);
                        updateButtonStates(productId, newQuantity, maxQty);

                        showSuccess('Quantity updated successfully');
                    } else {
                        showError(data.message || 'Failed to update quantity');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again.');
                } finally {
                    showLoading(productId, false);
                }
            }

            // Remove item function
            async function removeItem(productId) {
                showLoading(productId, true);

                try {
                    const response = await fetch(`{{ route("wholesaler.shop.cart.remove", "") }}/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Animate removal
                        const cartItem = document.getElementById(`cart-item-${productId}`);
                        cartItem.style.opacity = '0';
                        cartItem.style.transform = 'translateX(-50px)';

                        setTimeout(() => {
                            cartItem.remove();
                            updateCartSummary(data);

                            // Check if cart is empty
                            if (data.cart_count === 0) {
                                location.reload();
                            }
                        }, 300);

                        showSuccess('Item removed from cart');
                    } else {
                        showError(data.message || 'Failed to remove item');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again.');
                } finally {
                    showLoading(productId, false);
                }
            }

            // Clear cart function
            async function clearCart() {
                try {
                    const response = await fetch('{{ route("wholesaler.shop.cart.clear") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: 'Cart Cleared!',
                            text: 'Your cart has been cleared successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        showError('Failed to clear cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('An error occurred. Please try again.');
                }
            }

            // Update cart item UI
            function updateCartItemUI(productId, newQuantity, data) {
                const priceElement = document.querySelector(`#cart-item-${productId} .text-primary.h5`);
                if (priceElement && data.item_total) {
                    priceElement.textContent = `Rs. ${parseFloat(data.item_total).toFixed(2)}`;
                }
            }

            // Update button states
            function updateButtonStates(productId, newQuantity, maxQty) {
                const decrementBtn = document.querySelector(`.decrement-qty[data-product-id="${productId}"]`);
                const incrementBtn = document.querySelector(`.increment-qty[data-product-id="${productId}"]`);

                if (decrementBtn) decrementBtn.disabled = newQuantity <= 1;
                if (incrementBtn) incrementBtn.disabled = newQuantity >= maxQty;
            }

            // Update cart summary
            function updateCartSummary(data) {
                if (data.subtotal !== undefined) {
                    document.getElementById('subtotalAmount').textContent = `Rs. ${parseFloat(data.subtotal).toFixed(2)}`;

                    const subtotal = parseFloat(data.subtotal) || 0;
                    const tax = subtotal * 0.15;
                    const total = subtotal + tax;

                    document.getElementById('taxAmount').textContent = `Rs. ${tax.toFixed(2)}`;
                    document.getElementById('totalAmount').textContent = `Rs. ${total.toFixed(2)}`;
                }

                // Update cart count badge
                if (data.cart_count !== undefined) {
                    const cartBadges = document.querySelectorAll('.cart-count-badge');
                    cartBadges.forEach(badge => {
                        const icon = badge.querySelector('.material-icons-round');
                        badge.innerHTML = '';
                        if (icon) badge.appendChild(icon);
                        badge.appendChild(document.createTextNode(` ${data.cart_count} Items`));
                    });
                }
            }

            // Show loading overlay
            function showLoading(productId, show) {
                const loadingOverlay = document.getElementById(`loading-${productId}`);
                if (loadingOverlay) {
                    loadingOverlay.classList.toggle('active', show);
                }
            }

            // Notification functions
            function showSuccess(message) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal2-toast'
                    }
                });
            }

            function showError(message) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal2-toast'
                    }
                });
            }

            function showWarning(message) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal2-toast'
                    }
                });
            }

            // Add animation to buttons
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>
@endpush
