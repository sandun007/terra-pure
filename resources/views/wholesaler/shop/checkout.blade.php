@extends('wholesaler.layouts.app')
@push('title')
    Secure Checkout - {{ $shop->shop_name ?? 'Shop' }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --border-color: #e0e6ed;
        }

        .checkout-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding: 30px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .checkout-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
        }

        .checkout-progress {
            display: flex;
            justify-content: space-between;
            max-width: 800px;
            margin: 30px auto;
            position: relative;
        }

        .checkout-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            font-size: 18px;
            color: #95a5a6;
            transition: all 0.3s ease;
        }

        .progress-step.active .step-circle {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .progress-step.completed .step-circle {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .step-label {
            font-size: 14px;
            font-weight: 600;
            color: #7f8c8d;
            transition: all 0.3s ease;
        }

        .progress-step.active .step-label {
            color: var(--secondary-color);
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }

        .checkout-form-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .checkout-summary-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: sticky;
            top: 30px;
            height: fit-content;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-bg);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--secondary-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-text);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--accent-color);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .payment-method {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .payment-method:hover {
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.05);
        }

        .payment-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .payment-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .payment-desc {
            font-size: 13px;
            color: #7f8c8d;
        }

        .summary-items {
            margin-bottom: 25px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-price {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .item-quantity {
            color: #7f8c8d;
            font-size: 14px;
        }

        .summary-totals {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 15px;
        }

        .total-row.final {
            border-top: 2px solid var(--border-color);
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .place-order-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .place-order-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(39, 174, 96, 0.3);
        }

        .place-order-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .place-order-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .place-order-btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .security-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--light-bg);
            border-radius: 20px;
            font-size: 13px;
            color: var(--dark-text);
        }

        .invalid-feedback {
            color: var(--accent-color);
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .invalid-feedback.show {
            display: block;
        }

        .notes-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* SweetAlert Custom Styles */
        .swal2-popup {
            border-radius: 20px !important;
            padding: 30px !important;
        }

        .swal2-title {
            font-size: 24px !important;
            font-weight: 700 !important;
            color: #2c3e50 !important;
        }

        .swal2-confirm {
            border-radius: 10px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
        }

        .form-check-input.is-invalid {
            border-color: var(--accent-color);
        }
    </style>
@endpush

@section('content')
    <div class="checkout-wrapper">
        <div class="container checkout-container">
            <!-- Checkout Header -->
            <div class="checkout-header">
                <h1 class="mb-3"><i class="fas fa-lock me-2"></i>Secure Checkout</h1>
                <p class="text-muted">Complete your purchase with confidence</p>

                <!-- Progress Steps -->
                <div class="checkout-progress">
                    <div class="progress-step active">
                        <div class="step-circle">1</div>
                        <div class="step-label">Information</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-circle">2</div>
                        <div class="step-label">Review</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Payment</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-circle">4</div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>
            </div>

            <!-- Checkout Grid -->
            <div class="checkout-grid">
                <!-- Left Column: Checkout Form -->
                <div class="checkout-form-card">
                    <h2 class="section-title"><i class="fas fa-user"></i>Customer Information</h2>

                    <form id="checkoutForm" novalidate>
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text"
                                       name="customer_name"
                                       class="form-control"
                                       value="{{ Auth::guard('wholesaler')->user()->name ?? '' }}"
                                       required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email"
                                       name="customer_email"
                                       class="form-control"
                                       value="{{ Auth::guard('wholesaler')->user()->email ?? '' }}"
                                       required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel"
                                       name="customer_phone"
                                       class="form-control"
                                       value="{{ Auth::guard('wholesaler')->user()->phone ?? '' }}"
                                       required
                                       pattern="^[\+]?[0-9\s\-\(\)]{10,15}$"
                                       title="Enter a valid phone number (10-15 digits)">
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Shipping Address *</label>
                            <textarea name="shipping_address"
                                      class="form-control"
                                      rows="3"
                                      required>{{ Auth::guard('wholesaler')->user()->address ?? '' }}</textarea>
                            <div class="invalid-feedback" id="addressError"></div>
                        </div>

                        <h2 class="section-title mt-4"><i class="fas fa-credit-card"></i>Payment Method</h2>

                        <div class="payment-methods">
                            <div class="payment-method selected" data-method="cash_on_delivery">
                                <div class="payment-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="payment-name">Cash on Delivery</div>
                                <div class="payment-desc">Pay when you receive</div>
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked hidden>
                            </div>

                            <div class="payment-method" data-method="bank_transfer">
                                <div class="payment-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-name">Bank Transfer</div>
                                <div class="payment-desc">Direct bank payment</div>
                                <input type="radio" name="payment_method" value="bank_transfer" hidden>
                            </div>

                            <div class="payment-method" data-method="credit_card">
                                <div class="payment-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-name">Credit Card</div>
                                <div class="payment-desc">Secure card payment</div>
                                <input type="radio" name="payment_method" value="credit_card" hidden>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea name="notes"
                                      class="form-control notes-textarea"
                                      placeholder="Add special instructions for your order..."></textarea>
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                I agree to the <a href="#" class="text-primary">Terms & Conditions</a> and
                                <a href="#" class="text-primary">Privacy Policy</a>
                            </label>
                            <div class="invalid-feedback" id="termsError">You must agree to the terms and conditions</div>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="checkout-summary-card">
                    <h2 class="section-title"><i class="fas fa-receipt"></i>Order Summary</h2>

                    <div class="summary-items">
                        @foreach($cartItems as $item)
                            <div class="summary-item">
                                <div class="item-image">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <div class="item-name">{{ $item['name'] }}</div>
                                    <div class="item-quantity">Quantity: {{ $item['quantity'] }}</div>
                                    <div class="item-price">Rs. {{ number_format($item['subtotal'], 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span id="subtotalDisplay">Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping</span>
                            <span id="shippingDisplay" class="text-success">+ Rs. {{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span>Tax ({{ $taxRate }}%)</span>
                            <span id="taxDisplay">+ Rs. {{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="total-row final">
                            <span>Total Amount</span>
                            <span id="totalDisplay" class="text-success">Rs. {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button class="place-order-btn" onclick="processCheckout()" id="placeOrderBtn">
                        <i class="fas fa-lock"></i>
                        <span id="btnText">Place Order</span>
                        <div class="spinner" id="orderSpinner"></div>
                    </button>

                    <!-- Security Badges -->
                    <div class="security-badges">
                        <div class="security-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>256-bit SSL Secure</span>
                        </div>
                        <div class="security-badge">
                            <i class="fas fa-lock"></i>
                            <span>Safe Payment</span>
                        </div>
                        <div class="security-badge">
                            <i class="fas fa-undo"></i>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select payment method
            document.querySelectorAll('.payment-method').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.payment-method').forEach(c => {
                        c.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    const method = this.dataset.method;
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });

            // Add form validation on blur
            document.querySelectorAll('#checkoutForm input, #checkoutForm textarea').forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    clearError(this.name);
                });
            });

            // Terms checkbox validation
            const termsCheck = document.getElementById('termsCheck');
            termsCheck.addEventListener('change', function() {
                clearError('termsCheck');
            });
        });

        // Clear error message
        function clearError(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.remove('is-invalid');
            }

            const errorDiv = document.getElementById(`${fieldName}Error`);
            if (errorDiv) {
                errorDiv.classList.remove('show');
            }

            // Special handling for terms checkbox
            if (fieldName === 'termsCheck') {
                const termsCheck = document.getElementById('termsCheck');
                if (termsCheck) {
                    termsCheck.classList.remove('is-invalid');
                }
                const termsError = document.getElementById('termsError');
                if (termsError) {
                    termsError.classList.remove('show');
                }
            }
        }

        // Show error message
        function showError(fieldName, message) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
            }

            const errorDiv = document.getElementById(`${fieldName}Error`);
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            }

            // Special handling for terms checkbox
            if (fieldName === 'termsCheck') {
                const termsCheck = document.getElementById('termsCheck');
                if (termsCheck) {
                    termsCheck.classList.add('is-invalid');
                }
                const termsError = document.getElementById('termsError');
                if (termsError) {
                    termsError.classList.add('show');
                }
            }
        }

        // Validate individual field
        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;

            if (field.required && !value) {
                showError(fieldName, 'This field is required');
                return false;
            }

            // Email validation
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    showError(fieldName, 'Please enter a valid email address');
                    return false;
                }
            }

            // Phone validation - Fix for Sri Lankan phone numbers
            if (fieldName === 'customer_phone' && value) {
                // Allow formats: 0771234567, +94771234567, 0112345678
                const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,15}$/;
                if (!phoneRegex.test(value)) {
                    showError(fieldName, 'Please enter a valid phone number (10-15 digits)');
                    return false;
                }

                // Check if it contains at least 10 digits
                const digits = value.replace(/[^\d]/g, '');
                if (digits.length < 10) {
                    showError(fieldName, 'Phone number must have at least 10 digits');
                    return false;
                }
            }

            clearError(fieldName);
            return true;
        }

        // Validate entire form
        function validateForm() {
            let isValid = true;

            // Validate required fields
            const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'shipping_address'];
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            // Validate terms checkbox
            const termsCheck = document.getElementById('termsCheck');
            if (!termsCheck.checked) {
                showError('termsCheck', 'You must agree to the terms and conditions');
                isValid = false;
            }

            return isValid;
        }

        // Process checkout
        async function processCheckout() {
            const btn = document.getElementById('placeOrderBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('orderSpinner');

            console.log('Checkout process started...');

            // Validate form
            if (!validateForm()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please fix the errors in the form before submitting.',
                    confirmButtonColor: '#3498db',
                });
                return;
            }

            // Show loading state
            btn.disabled = true;
            btnText.textContent = 'Processing...';
            spinner.style.display = 'inline-block';

            try {
                // Get form data
                const formData = {
                    customer_name: document.querySelector('[name="customer_name"]').value.trim(),
                    customer_email: document.querySelector('[name="customer_email"]').value.trim(),
                    customer_phone: document.querySelector('[name="customer_phone"]').value.trim(),
                    shipping_address: document.querySelector('[name="shipping_address"]').value.trim(),
                    payment_method: document.querySelector('[name="payment_method"]:checked').value,
                    notes: document.querySelector('[name="notes"]').value.trim(),
                    _token: '{{ csrf_token() }}'
                };

                console.log('Form data:', formData);

                // Send request
                const response = await fetch('{{ route("wholesaler.shop.checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Server response:', data);

                if (response.ok && data.success) {
                    // Reset button
                    resetButton(btn, btnText, spinner);

                    // Show SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                            <div style="text-align: center;">
                                <div style="font-size: 60px; color: #27ae60; margin-bottom: 20px;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3>Thank You for Your Order!</h3>
                                <p style="color: #666; margin-bottom: 20px;">
                                    Your order #<strong>${data.order_number}</strong> has been confirmed.
                                </p>
                                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0;">
                                    <strong>Order ID:</strong> ${data.order_number}
                                </div>
                                <p style="color: #666;">
                                    You will receive a confirmation email shortly.
                                </p>
                            </div>
                        `,
                        showCancelButton: false,
                        confirmButtonText: 'View Order Details',
                        confirmButtonColor: '#27ae60',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed && data.redirect_url) {
                            // Redirect to order details page
                            window.location.href = data.redirect_url;
                        } else {
                            // Fallback redirect
                            setTimeout(() => {
                                window.location.href = '{{ route("wholesaler.orders.index") }}';
                            }, 3000);
                        }
                    });

                    // Clear cart from session
                    clearCart();

                } else {
                    // Handle errors
                    console.error('Server error:', data);

                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            showError(field, data.errors[field][0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please fix the errors in the form.',
                            confirmButtonColor: '#e74c3c',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Order Failed',
                            text: data.message || 'An error occurred while processing your order.',
                            confirmButtonColor: '#e74c3c',
                        });
                    }

                    // Reset button
                    resetButton(btn, btnText, spinner);
                }

            } catch (error) {
                console.error('Checkout error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Please check your connection and try again.',
                    confirmButtonColor: '#e74c3c',
                });
                resetButton(btn, btnText, spinner);
            }
        }

        // Reset button state
        function resetButton(btn, btnText, spinner) {
            btn.disabled = false;
            btnText.textContent = 'Place Order';
            spinner.style.display = 'none';
        }

        // Clear cart from session
        function clearCart() {
            // Clear cart from localStorage
            if (typeof localStorage !== 'undefined') {
                localStorage.removeItem('wholesaler_cart');
            }

            // Clear cart from session via AJAX
            fetch('{{ route("wholesaler.shop.cart.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    console.log('Cart cleared:', data);
                })
                .catch(err => console.log('Error clearing cart:', err));
        }

        // Add Enter key support for form submission
        document.addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && !event.target.matches('textarea')) {
                event.preventDefault();
            }
        });
    </script>
@endpush
