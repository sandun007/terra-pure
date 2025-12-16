@extends('wholesaler.layouts.app')
@push('title')
    Checkout - {{ $shop->shop_name ?? 'Shop' }}
@endpush
@push('css')
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        .checkout-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .checkout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 2rem 0;
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .checkout-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .checkout-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .payment-method-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .payment-method-card.selected {
            border-color: var(--primary-color);
            background: #f0f9ff;
        }

        .order-summary {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            position: sticky;
            top: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item.total {
            border-top: 2px solid #e5e7eb;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            margin-right: 1rem;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 18px;
            font-size: 1.125rem;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }

        .btn-checkout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-checkout:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .security-badge {
            background: var(--success-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 1rem;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .checkout-error {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .coupon-box {
            background: #fef3c7;
            border: 2px dashed #f59e0b;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .is-invalid {
            border-color: var(--danger-color) !important;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .step.completed .step-circle {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
        }

        .step.active .step-label {
            color: var(--primary-color);
        }
    </style>
@endpush

@section('content')
    <div class="checkout-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="checkout-card">
                        <!-- Progress Steps -->
                        <div class="progress-steps">
                            <div class="step completed">
                                <div class="step-circle">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="step-label">Cart</div>
                            </div>
                            <div class="step active">
                                <div class="step-circle">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="step-label">Information</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="step-label">Shipping</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="step-label">Payment</div>
                            </div>
                        </div>

                        <div class="checkout-header">
                            <h1 class="h2 fw-bold mb-3">
                                <i class="fas fa-lock me-2"></i>
                                Secure Checkout
                            </h1>
                            <p class="mb-0">Complete your purchase in just a few steps</p>
                        </div>

                        <div class="checkout-body">
                            <div class="row">
                                <!-- Left Column: Checkout Form -->
                                <div class="col-lg-8">
                                    <form id="checkoutForm">
                                        <!-- Customer Information -->
                                        <div class="mb-4">
                                            <h4 class="fw-bold mb-3">
                                                <i class="fas fa-user me-2"></i>
                                                Customer Information
                                            </h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Full Name *</label>
                                                        <input type="text" name="customer_name" class="form-control"
                                                               value="{{ $wholesalerInfo['name'] ?? '' }}" required>
                                                        <div class="checkout-error" id="nameError"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Email Address *</label>
                                                        <input type="email" name="customer_email" class="form-control"
                                                               value="{{ $wholesalerInfo['email'] ?? '' }}" required>
                                                        <div class="checkout-error" id="emailError"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone Number *</label>
                                                        <input type="tel" name="customer_phone" class="form-control"
                                                               value="{{ $wholesalerInfo['phone'] ?? '' }}" required>
                                                        <div class="checkout-error" id="phoneError"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Shipping Address -->
                                        <div class="mb-4">
                                            <h4 class="fw-bold mb-3">
                                                <i class="fas fa-truck me-2"></i>
                                                Shipping Address
                                            </h4>
                                            <div class="form-group">
                                                <label class="form-label">Full Address *</label>
                                                <textarea name="shipping_address" class="form-control" rows="3" required>{{ $wholesalerInfo['address'] ?? '' }}</textarea>
                                                <div class="checkout-error" id="addressError"></div>
                                            </div>
                                        </div>

                                        <!-- Payment Method -->
                                        <div class="mb-4">
                                            <h4 class="fw-bold mb-3">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Payment Method
                                            </h4>
                                            <div class="payment-method-card selected" data-method="cash_on_delivery">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                               value="cash_on_delivery" checked>
                                                    </div>
                                                    <div class="payment-icon me-3">
                                                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">Cash on Delivery</h6>
                                                        <p class="text-muted mb-0">Pay when you receive your order</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-method-card" data-method="bank_transfer">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                               value="bank_transfer">
                                                    </div>
                                                    <div class="payment-icon me-3">
                                                        <i class="fas fa-university fa-2x text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">Bank Transfer</h6>
                                                        <p class="text-muted mb-0">Transfer directly to our bank account</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-method-card" data-method="credit_card">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                               value="credit_card">
                                                    </div>
                                                    <div class="payment-icon me-3">
                                                        <i class="fas fa-credit-card fa-2x text-warning"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">Credit/Debit Card</h6>
                                                        <p class="text-muted mb-0">Pay securely with your card</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Notes -->
                                        <div class="mb-4">
                                            <h4 class="fw-bold mb-3">
                                                <i class="fas fa-sticky-note me-2"></i>
                                                Order Notes
                                            </h4>
                                            <div class="form-group">
                                                <textarea name="notes" class="form-control" rows="3"
                                                          placeholder="Add special instructions for your order..."></textarea>
                                            </div>
                                        </div>

                                        @csrf
                                    </form>
                                </div>

                                <!-- Right Column: Order Summary -->
                                <div class="col-lg-4">
                                    <div class="order-summary">
                                        <h4 class="fw-bold mb-3">
                                            <i class="fas fa-receipt me-2"></i>
                                            Order Summary
                                        </h4>

                                        <!-- Order Items -->
                                        <div class="mb-4">
                                            @foreach($cartItems as $item)
                                                <div class="product-item">
                                                    <div class="product-image">
                                                        @if($item['image'])
                                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                        @else
                                                            <i class="fas fa-box text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                                        <p class="text-muted mb-1">Qty: {{ $item['quantity'] }}</p>
                                                        <p class="fw-bold mb-0 text-primary">
                                                            Rs. {{ number_format($item['subtotal'], 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Price Breakdown -->
                                        <div class="summary-item">
                                            <span>Subtotal</span>
                                            <span id="subtotal">Rs. {{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Shipping</span>
                                            <span id="shipping" class="text-success">Rs. {{ number_format($shipping, 2) }}</span>
                                        </div>
                                        <div class="summary-item">
                                            <span>Tax ({{ $taxRate }}%)</span>
                                            <span id="tax">Rs. {{ number_format($tax, 2) }}</span>
                                        </div>
                                        <div class="summary-item total">
                                            <span>Total Amount</span>
                                            <span id="grandTotal">Rs. {{ number_format($total, 2) }}</span>
                                        </div>

                                        <!-- Terms and Conditions -->
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                            <label class="form-check-label small" for="termsCheck">
                                                I agree to the <a href="#" class="text-primary">Terms & Conditions</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                            </label>
                                        </div>

                                        <!-- Place Order Button -->
                                        <button class="btn-checkout" onclick="processCheckout()" id="placeOrderBtn">
                                            <i class="fas fa-lock me-2"></i>
                                            Place Order
                                            <div class="loading-spinner" id="orderSpinner"></div>
                                        </button>

                                        <!-- Security Badge -->
                                        <div class="text-center mt-3">
                                            <div class="security-badge">
                                                <i class="fas fa-shield-alt"></i>
                                                256-bit SSL Secured
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.payment-method-card').forEach(c => {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
                const method = this.dataset.method;
                document.querySelector(`input[value="${method}"]`).checked = true;
            });
        });

        // Process checkout
        async function processCheckout() {
            const btn = document.getElementById('placeOrderBtn');
            const spinner = document.getElementById('orderSpinner');

            // Validate form
            const form = document.getElementById('checkoutForm');
            const termsCheck = document.getElementById('termsCheck');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!termsCheck.checked) {
                showNotification('error', 'Please agree to the Terms & Conditions');
                return;
            }

            // Show loading
            btn.disabled = true;
            spinner.style.display = 'inline-block';

            // Get form data
            const formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            try {
                const response = await fetch('{{ route("wholesaler.shop.checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formObject)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('success', data.message);

                    // Redirect to order page
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showNotification('error', data.message);
                    btn.disabled = false;
                    spinner.style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        }

        // Show notification
        function showNotification(type, message) {
            // Remove existing notifications
            document.querySelectorAll('.alert-notification').forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-notification alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Validate form inputs in real-time
            const formInputs = document.querySelectorAll('#checkoutForm input, #checkoutForm textarea');
            formInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    clearError(this.name);
                });
            });
        });

        function validateField(field) {
            const errorId = `${field.name}Error`;
            const errorDiv = document.getElementById(errorId);

            if (!field.checkValidity()) {
                if (errorDiv) {
                    errorDiv.textContent = field.validationMessage;
                    errorDiv.style.display = 'block';
                }
                field.classList.add('is-invalid');
            } else {
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
                field.classList.remove('is-invalid');
            }
        }

        function clearError(fieldName) {
            const errorDiv = document.getElementById(`${fieldName}Error`);
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.remove('is-invalid');
            }
        }
    </script>
@endpush
