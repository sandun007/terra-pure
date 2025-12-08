@extends('wholesaler.layouts.app')
@push('title')
    Shopping Cart - {{ $shop->shop_name ?? 'Shop' }}
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

        .cart-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .cart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            flex-shrink: 0;
        }

        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
            padding: 0 1.5rem;
        }

        .cart-item-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .cart-item-meta {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .cart-item-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .qty-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .qty-input {
            width: 60px;
            height: 40px;
            border: none;
            text-align: center;
            font-weight: 600;
        }

        .remove-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: #dc2626;
            color: white;
        }

        .cart-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid #bae6fd;
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #cbd5e1;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .summary-row.total {
            border-top: 2px solid #cbd5e1;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-cart-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .btn-continue {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(58, 134, 255, 0.3);
        }

        .btn-checkout {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 0, 110, 0.3);
        }

        .btn-clear {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-clear:hover {
            background: #dc2626;
            color: white;
        }

        .cart-promo {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 2px dashed #cbd5e1;
        }

        .promo-input {
            display: flex;
            gap: 10px;
        }

        .promo-input input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
        }

        .btn-apply {
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .cart-item-details {
                padding: 1rem 0;
            }

            .cart-item-actions {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="cart-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-2">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Shopping Cart
                    </h1>
                    <p class="mb-0">Review your items before checkout</p>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold mb-0">{{ count($cart) }} Items</h3>
                    <small>in your cart</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if(empty($cart))
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('wholesaler.shop.index') }}" class="btn-continue">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-4">
                    <div class="cart-container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0">Cart Items</h3>
                            <button class="btn-clear" onclick="clearCart()">
                                <i class="fas fa-trash me-2"></i>
                                Clear Cart
                            </button>
                        </div>

                        @foreach($cart as $key => $item)
                            @php
                                $cartKey = str_replace('product_', '', $key);
                            @endphp
                            <div class="cart-item" id="cartItem{{ $cartKey }}">
                                <!-- Product Image -->
                                <div class="cart-item-image">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-box-open text-muted fa-2x"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="cart-item-details">
                                    <h4 class="cart-item-title">{{ $item['name'] }}</h4>
                                    <div class="cart-item-meta">
                                        <span class="me-3">
                                            <i class="fas fa-barcode me-1"></i>
                                            {{ $item['code'] }}
                                        </span>
                                        <span class="me-3">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $item['category'] }}
                                        </span>
                                        <span>
                                            <i class="fas fa-industry me-1"></i>
                                            {{ $item['brand'] }}
                                        </span>
                                    </div>
                                    <div class="cart-item-price">
                                        Rs. {{ number_format($item['price'] * $item['quantity'], 2) }}
                                        <small class="text-muted ms-2">
                                            (Rs. {{ number_format($item['price'], 2) }} each)
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Minimum order: {{ $item['min_order_quantity'] }} units
                                        </small>
                                    </div>
                                </div>

                                <!-- Quantity Control -->
                                <div class="cart-item-actions">
                                    <div class="quantity-control">
                                        <button class="qty-btn" onclick="updateQuantity('{{ $cartKey }}', -1)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number"
                                               class="qty-input"
                                               id="qty{{ $cartKey }}"
                                               value="{{ $item['quantity'] }}"
                                               min="{{ $item['min_order_quantity'] }}"
                                               max="{{ $item['max_quantity'] }}"
                                               onchange="updateQuantityInput('{{ $cartKey }}', this.value)">
                                        <button class="qty-btn" onclick="updateQuantity('{{ $cartKey }}', 1)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>

                                    <!-- Remove Button -->
                                    <button class="remove-btn" onclick="removeFromCart('{{ $cartKey }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Continue Shopping -->
                        <div class="text-center mt-4">
                            <a href="{{ route('wholesaler.shop.index') }}" class="btn-continue">
                                <i class="fas fa-arrow-left me-2"></i>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h3 class="summary-title">Order Summary</h3>

                        <!-- Summary Details -->
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotal">Rs. {{ number_format($cartTotal, 2) }}</span>
                        </div>

                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Free
                            </span>
                        </div>

                        <div class="summary-row">
                            <span>Tax ({{ $shop->tax_rate ?? 0 }}%)</span>
                            <span id="tax">Rs. {{ number_format(0, 2) }}</span>
                        </div>

                        <div class="summary-row">
                            <span>Discount</span>
                            <span class="text-success" id="discount">- Rs. 0.00</span>
                        </div>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="grandTotal">Rs. {{ number_format($cartTotal, 2) }}</span>
                        </div>

                        <!-- Promo Code -->
                        <div class="cart-promo">
                            <h6 class="mb-3">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Have a promo code?
                            </h6>
                            <div class="promo-input">
                                <input type="text" id="promoCode" placeholder="Enter promo code">
                                <button class="btn-apply" onclick="applyPromo()">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('wholesaler.shop.checkout') }}" class="btn-checkout">
                            <i class="fas fa-lock me-2"></i>
                            Proceed to Checkout
                        </a>

                        <!-- Payment Methods -->
                        <div class="mt-4">
                            <h6 class="mb-3">We accept:</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark p-2">
                                    <i class="fab fa-cc-visa fa-2x"></i>
                                </span>
                                <span class="badge bg-light text-dark p-2">
                                    <i class="fab fa-cc-mastercard fa-2x"></i>
                                </span>
                                <span class="badge bg-light text-dark p-2">
                                    <i class="fab fa-cc-paypal fa-2x"></i>
                                </span>
                                <span class="badge bg-light text-dark p-2">
                                    <i class="fas fa-university fa-2x"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="fas fa-shield-alt text-success"></i>
                                <small>Secure checkout • 256-bit SSL encryption</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        // Update quantity with buttons
        function updateQuantity(productId, change) {
            const input = document.getElementById(`qty${productId}`);
            const currentQty = parseInt(input.value);
            const minQty = parseInt(input.min);
            const maxQty = parseInt(input.max);

            let newQty = currentQty + change;

            if (newQty < minQty) {
                newQty = minQty;
                showToast('info', `Minimum order quantity is ${minQty}`);
            } else if (newQty > maxQty) {
                newQty = maxQty;
                showToast('error', `Maximum available quantity is ${maxQty}`);
            }

            if (newQty !== currentQty) {
                updateCartItem(productId, newQty);
            }
        }

        // Update quantity from input
        function updateQuantityInput(productId, value) {
            const input = document.getElementById(`qty${productId}`);
            const minQty = parseInt(input.min);
            const maxQty = parseInt(input.max);
            let qty = parseInt(value);

            if (isNaN(qty) || qty < minQty) {
                qty = minQty;
                input.value = minQty;
                showToast('info', `Minimum order quantity is ${minQty}`);
            } else if (qty > maxQty) {
                qty = maxQty;
                input.value = maxQty;
                showToast('error', `Maximum available quantity is ${maxQty}`);
            }

            updateCartItem(productId, qty);
        }

        // Update cart item via AJAX
        function updateCartItem(productId, quantity) {
            $.ajax({
                url: '{{ route("wholesaler.shop.cart.update") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update quantity display
                        document.getElementById(`qty${productId}`).value = quantity;

                        // Update cart count
                        updateCartCount(response.cart_count);

                        // Update totals
                        updateCartTotals(response.cart_total);

                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Error updating cart');
                }
            });
        }

        // Remove item from cart
        function removeFromCart(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            $.ajax({
                url: '{{ route("wholesaler.shop.cart.remove", "") }}/' + productId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Remove item from DOM
                        document.getElementById(`cartItem${productId}`).remove();

                        // Update cart count
                        updateCartCount(response.cart_count);

                        // Update totals
                        updateCartTotals(response.cart_total);

                        showToast('success', response.message);

                        // If cart is empty, reload page
                        if (response.cart_count === 0) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Error removing item');
                }
            });
        }

        // Clear entire cart
        function clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            $.ajax({
                url: '{{ route("wholesaler.shop.cart.clear") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        updateCartCount(response.cart_count);

                        // Update totals
                        updateCartTotals(response.cart_total);

                        showToast('success', response.message);

                        // Reload page to show empty cart
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    showToast('error', 'Error clearing cart');
                }
            });
        }

        // Apply promo code
        function applyPromo() {
            const promoCode = document.getElementById('promoCode').value.trim();

            if (!promoCode) {
                showToast('error', 'Please enter a promo code');
                return;
            }

            // Mock implementation - replace with actual API call
            showToast('info', `Promo code "${promoCode}" applied successfully!`);
            // Here you would make an AJAX call to validate and apply the promo code
        }

        // Update cart totals
        function updateCartTotals(total) {
            const subtotal = document.getElementById('subtotal');
            const grandTotal = document.getElementById('grandTotal');

            if (subtotal) {
                subtotal.textContent = `Rs. ${parseFloat(total).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;
            }

            if (grandTotal) {
                grandTotal.textContent = `Rs. ${parseFloat(total).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;
            }
        }

        // Update cart count
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

        // Toast notification
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

        // Initialize cart count on page load
        $(document).ready(function() {
            $.get('{{ route("wholesaler.shop.cart.count") }}', function(response) {
                updateCartCount(response.count);
            });
        });
    </script>
@endpush
