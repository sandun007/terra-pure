@extends('wholesaler.layouts.app')
@push('title')
    Checkout - {{ $shop->shop_name ?? 'Shop' }}
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

        .checkout-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .checkout-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 2rem 0;
        }

        .checkout-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: #e2e8f0;
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 4px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #94a3b8;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }

        .step-circle.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .step-circle.completed {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .step-label {
            font-weight: 600;
            color: #64748b;
        }

        .step-label.active {
            color: var(--primary-color);
        }

        .checkout-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .address-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .address-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .address-card.selected {
            border-color: var(--primary-color);
            background: #f0f9ff;
        }

        .payment-method {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: #f0f9ff;
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .order-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid #bae6fd;
            position: sticky;
            top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .summary-item.total {
            border-top: 2px solid #cbd5e1;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #f1f5f9;
            margin-right: 15px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .btn-checkout {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
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
            box-shadow: 0 10px 25px rgba(58, 134, 255, 0.3);
        }

        .security-badge {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 1rem;
        }

        .form-control-lg {
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
        }

        .form-control-lg:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.1);
        }

        .coupon-box {
            background: #fef3c7;
            border: 2px dashed #f59e0b;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .checkout-progress {
                flex-direction: column;
                gap: 20px;
            }

            .checkout-progress::before {
                display: none;
            }

            .progress-step {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .step-circle {
                margin: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="checkout-header">
        <div class="container">
            <h1 class="fw-bold mb-2">
                <i class="fas fa-lock me-2"></i>
                Secure Checkout
            </h1>
            <p class="mb-0">Complete your purchase in just a few steps</p>
        </div>
    </div>

    <div class="container">
        <!-- Progress Steps -->
        <div class="checkout-progress">
            <div class="progress-step">
                <div class="step-circle active">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="step-label active">Cart</div>
            </div>
            <div class="progress-step">
                <div class="step-circle active">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label active">Information</div>
            </div>
            <div class="progress-step">
                <div class="step-circle active">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="step-label active">Shipping</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="step-label">Payment</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Checkout Form -->
            <div class="col-lg-8 mb-4">
                <!-- Shipping Information -->
                <div class="checkout-card">
                    <h3 class="section-title">
                        <i class="fas fa-truck"></i>
                        Shipping Information
                    </h3>

                    <form id="shippingForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Shipping Address *</label>
                            <textarea class="form-control form-control-lg" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Country *</label>
                            <select class="form-control form-control-lg" required>
                                <option value="">Select Country</option>
                                <option value="PK">Pakistan</option>
                                <!-- Add more countries -->
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Payment Method -->
                <div class="checkout-card">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        Payment Method
                    </h3>

                    <div class="payment-method selected" onclick="selectPayment('credit_card')">
                        <div class="d-flex align-items-center">
                            <div class="payment-icon text-primary">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Credit/Debit Card</h6>
                                <p class="text-muted mb-0">Pay securely with your card</p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" checked>
                            </div>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPayment('bank_transfer')">
                        <div class="d-flex align-items-center">
                            <div class="payment-icon text-success">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Bank Transfer</h6>
                                <p class="text-muted mb-0">Transfer directly to our bank account</p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod">
                            </div>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPayment('cod')">
                        <div class="d-flex align-items-center">
                            <div class="payment-icon text-warning">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Cash on Delivery</h6>
                                <p class="text-muted mb-0">Pay when you receive your order</p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod">
                            </div>
                        </div>
                    </div>

                    <!-- Card Details (Show only when credit card selected) -->
                    <div id="cardDetails" class="mt-4">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Card Number *</label>
                                <input type="text" class="form-control form-control-lg" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date *</label>
                                <input type="text" class="form-control form-control-lg" placeholder="MM/YY">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-control form-control-lg" placeholder="123">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Cardholder Name *</label>
                                <input type="text" class="form-control form-control-lg" placeholder="John Doe">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="checkout-card">
                    <h3 class="section-title">
                        <i class="fas fa-sticky-note"></i>
                        Order Notes
                    </h3>
                    <textarea class="form-control form-control-lg" rows="4"
                              placeholder="Add special instructions for your order..."></textarea>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="section-title">
                        <i class="fas fa-receipt"></i>
                        Order Summary
                    </h3>

                    <!-- Order Items -->
                    <div class="mb-4">
                        @foreach($cart as $item)
                            <div class="product-item">
                                <div class="product-image">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <p class="text-muted mb-1">Qty: {{ $item['quantity'] }}</p>
                                    <p class="mb-0 fw-bold">Rs. {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span id="subtotal">Rs. {{ number_format($cartTotal, 2) }}</span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    <div class="summary-item">
                        <span>Tax ({{ $shop->tax_rate ?? 0 }}%)</span>
                        <span id="tax">Rs. 0.00</span>
                    </div>

                    <div class="summary-item" id="discountRow" style="display: none;">
                        <span>Discount</span>
                        <span class="text-success" id="discount">- Rs. 0.00</span>
                    </div>

                    <div class="summary-item total">
                        <span>Total</span>
                        <span id="grandTotal">Rs. {{ number_format($cartTotal, 2) }}</span>
                    </div>

                    <!-- Coupon Code -->
                    <div class="coupon-box">
                        <h6 class="mb-3">
                            <i class="fas fa-tag me-2"></i>
                            Apply Coupon
                        </h6>
                        <div class="input-group">
                            <input type="text" class="form-control" id="couponCode" placeholder="Enter coupon code">
                            <button class="btn btn-primary" onclick="applyCoupon()">
                                Apply
                            </button>
                        </div>
                        <div id="couponMessage" class="mt-2 small"></div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="termsCheck">
                        <label class="form-check-label small" for="termsCheck">
                            I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button class="btn-checkout" onclick="placeOrder()">
                        <i class="fas fa-lock me-2"></i>
                        Place Order
                    </button>

                    <!-- Security Badge -->
                    <div class="text-center mt-3">
                        <div class="security-badge">
                            <i class="fas fa-shield-alt"></i>
                            256-bit SSL Secured
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3">Need Help?</h6>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-phone text-primary"></i>
                            <small>{{ $shop->contact_phone ?? 'N/A' }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-envelope text-primary"></i>
                            <small>{{ $shop->contact_email ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Select payment method
        function selectPayment(method) {
            // Remove selected class from all
            document.querySelectorAll('.payment-method').forEach(card => {
                card.classList.remove('selected');
                card.querySelector('input[type="radio"]').checked = false;
            });

            // Add selected class to clicked
            event.currentTarget.classList.add('selected');
            event.currentTarget.querySelector('input[type="radio"]').checked = true;

            // Show/hide card details
            const cardDetails = document.getElementById('cardDetails');
            if (method === 'credit_card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        // Apply coupon
        function applyCoupon() {
            const couponCode = document.getElementById('couponCode').value.trim();
            const messageDiv = document.getElementById('couponMessage');

            if (!couponCode) {
                messageDiv.innerHTML = '<span class="text-danger">Please enter a coupon code</span>';
                return;
            }

            // Mock implementation - replace with actual API call
            // For demo, we'll simulate a 10% discount
            const subtotal = {{ $cartTotal }};
            const discount = subtotal * 0.1;
            const newTotal = subtotal - discount;

            // Update totals
            document.getElementById('subtotal').textContent = `Rs. ${subtotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;

            document.getElementById('discount').textContent = `- Rs. ${discount.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;

            document.getElementById('grandTotal').textContent = `Rs. ${newTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;

            // Show discount row
            document.getElementById('discountRow').style.display = 'flex';

            // Show success message
            messageDiv.innerHTML = '<span class="text-success">Coupon applied successfully! 10% discount applied.</span>';

            // Disable coupon input
            document.getElementById('couponCode').disabled = true;
            event.target.disabled = true;
            event.target.textContent = 'Applied';
        }

        // Place order
        function placeOrder() {
            // Validate form
            const shippingForm = document.getElementById('shippingForm');
            const termsCheck = document.getElementById('termsCheck');

            if (!shippingForm.checkValidity()) {
                showToast('error', 'Please fill in all required shipping information');
                shippingForm.reportValidity();
                return;
            }

            if (!termsCheck.checked) {
                showToast('error', 'Please agree to the Terms & Conditions');
                return;
            }

            // Validate payment method
            const selectedPayment = document.querySelector('.payment-method.selected input[type="radio"]');
            if (!selectedPayment) {
                showToast('error', 'Please select a payment method');
                return;
            }

            // Validate card details if credit card selected
            if (selectedPayment.value === 'credit_card') {
                // Add card validation here
            }

            // Show loading
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            btn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // For demo, we'll show success and redirect
                showToast('success', 'Order placed successfully!');

                // Redirect to order confirmation page
                setTimeout(() => {
                    window.location.href = '{{ route("wholesaler.orders.index") }}';
                }, 2000);

            }, 2000);
        }

        // Validate form inputs
        document.querySelectorAll('.form-control-lg').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.checkValidity()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

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

        // Initialize
        $(document).ready(function() {
            // Hide card details initially if not credit card
            const selectedPayment = document.querySelector('.payment-method.selected input[type="radio"]');
            if (selectedPayment && selectedPayment.value !== 'credit_card') {
                document.getElementById('cardDetails').style.display = 'none';
            }
        });
    </script>
@endpush
