@extends('wholesaler.layouts.app')

@section('title', 'Withdraw Funds')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .withdraw-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .balance-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
            padding: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .method-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .method-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .method-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
        }
        .amount-option {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .amount-option:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }
        .amount-option.selected {
            border-color: #007bff;
            background-color: #007bff;
            color: white;
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }
        .page-title-box {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .input-group-text {
            background: #f8f9fa;
            border-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Withdraw Funds</h4>
                            <a href="{{ route('wholesaler.payments.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Payments
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Balance Overview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="balance-display">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="display-4 text-white mb-1">RS. {{ number_format($availableBalance, 2) }}</h2>
                                    <p class="text-white-50 mb-0">Available for withdrawal</p>
                                    <div class="mt-3">
                                <span class="badge bg-light text-dark me-2">
                                    Min: RS. {{ number_format($minWithdrawal, 2) }}
                                </span>
                                        <span class="badge bg-light text-dark">
                                    Max: RS. {{ number_format($maxWithdrawal, 2) }}
                                </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <i class="fas fa-wallet display-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card withdraw-card">
                            <div class="card-body">
                                <form action="{{ route('wholesaler.payments.process-withdraw') }}" method="POST" id="withdrawForm">
                                    @csrf

                                    <!-- Add this to your existing withdraw.blade.php after payment methods section -->
                                    <!-- Gateway Withdrawal Option -->
                                    @if($paymentGateways->count() > 0)
                                        <div class="mb-4">
                                            <h5 class="mb-3">OR Withdraw via Payment Gateway</h5>
                                            <div class="row g-3">
                                                @foreach($paymentGateways as $gateway)
                                                    <div class="col-md-6">
                                                        <div class="method-card p-3" onclick="selectGateway({{ $gateway->id }})">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="gateway_id"
                                                                       value="{{ $gateway->id }}"
                                                                       id="gateway{{ $gateway->id }}">
                                                                <label class="form-check-label w-100" for="gateway{{ $gateway->id }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            @switch($gateway->gateway_type)
                                                                                @case('stripe')
                                                                                    <i class="fab fa-stripe fa-2x text-primary me-3"></i>
                                                                                    @break
                                                                                @case('paypal')
                                                                                    <i class="fab fa-paypal fa-2x text-info me-3"></i>
                                                                                    @break
                                                                                @default
                                                                                    <i class="fas fa-credit-card fa-2x text-success me-3"></i>
                                                                            @endswitch
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <h6 class="mb-1">
                                                                                {{ $gateway->gateway_name }}
                                                                                @if($gateway->is_default)
                                                                                    <span class="badge bg-success ms-2">Default</span>
                                                                                @endif
                                                                            </h6>
                                                                            <small class="text-muted">
                                                                                {{ $gateway->gateway_type }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <!-- Payment Method Selection -->
                                    <div class="mb-4">
                                        <h5 class="mb-3">Select Payment Method</h5>
                                        @if($paymentMethods->count() > 0)
                                            <div class="row g-3">
                                                @foreach($paymentMethods as $method)
                                                    <div class="col-md-6">
                                                        <div class="method-card p-3" onclick="selectMethod({{ $method->id }})">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                       name="payment_method_id"
                                                                       value="{{ $method->id }}"
                                                                       id="method{{ $method->id }}"
                                                                    {{ $loop->first ? 'checked' : '' }}>
                                                                <label class="form-check-label w-100" for="method{{ $method->id }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0">
                                                                            @if($method->method_type == 'bank')
                                                                                <i class="fas fa-university fa-2x text-primary me-3"></i>
                                                                            @else
                                                                                <i class="fas fa-mobile-alt fa-2x text-success me-3"></i>
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <h6 class="mb-1">
                                                                                {{ $method->method_type == 'bank' ? $method->bank_name : $method->mobile_provider }}
                                                                                @if($method->is_default)
                                                                                    <span class="badge bg-success ms-2">Default</span>
                                                                                @endif
                                                                            </h6>
                                                                            <p class="text-muted mb-1">
                                                                                {{ $method->account_name }}
                                                                            </p>
                                                                            <small class="text-muted">
                                                                                {{ $method->account_number }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                No payment methods found.
                                                <a href="{{ route('wholesaler.payments.methods.create') }}" class="alert-link">
                                                    Add a payment method
                                                </a> to withdraw funds.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Amount Selection -->
                                    <div class="mb-4">
                                        <h5 class="mb-3">Withdrawal Amount</h5>

                                        <!-- Quick Amount Options -->
                                        <div class="row g-2 mb-3">
                                            @php
                                                $quickAmounts = [
                                                    $minWithdrawal,
                                                    min(500, $maxWithdrawal),
                                                    min(1000, $maxWithdrawal),
                                                    min(2000, $maxWithdrawal),
                                                    $maxWithdrawal
                                                ];
                                                $quickAmounts = array_unique($quickAmounts);
                                            @endphp
                                            @foreach($quickAmounts as $amount)
                                                @if($amount >= $minWithdrawal && $amount <= $maxWithdrawal)
                                                    <div class="col">
                                                        <div class="amount-option" onclick="setAmount({{ $amount }})">
                                                            RS. {{ number_format($amount, 0) }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- Custom Amount Input -->
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Custom Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">RS.</span>
                                                <input type="number"
                                                       class="form-control form-control-lg"
                                                       id="amount"
                                                       name="amount"
                                                       min="{{ $minWithdrawal }}"
                                                       max="{{ $maxWithdrawal }}"
                                                       step="0.01"
                                                       placeholder="Enter amount"
                                                       required>
                                            </div>
                                            <div class="form-text">
                                                Minimum: RS. {{ number_format($minWithdrawal, 2) }} |
                                                Maximum: RS. {{ number_format($maxWithdrawal, 2) }}
                                            </div>
                                        </div>

                                        <!-- Amount Validation -->
                                        <div id="amountFeedback" class="alert d-none"></div>
                                    </div>

                                    <!-- Additional Notes -->
                                    <div class="mb-4">
                                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                        <textarea class="form-control"
                                                  id="notes"
                                                  name="notes"
                                                  rows="3"
                                                  placeholder="Any additional information for this withdrawal..."></textarea>
                                    </div>

                                    <!-- Terms and Submit -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I understand that withdrawal requests are processed within 24-48 hours and may be subject to verification.
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('wholesaler.payments.index') }}" class="btn btn-outline-secondary me-md-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Withdrawal Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Important Information</h6>
                            <ul class="mb-0">
                                <li>Withdrawal requests are processed within 24-48 hours</li>
                                <li>Minimum withdrawal amount: RS. {{ number_format($minWithdrawal, 2) }}</li>
                                <li>Ensure your payment method details are correct</li>
                                <li>You may be contacted for verification if needed</li>
                                <li>Processing times may vary during weekends and holidays</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add this to your existing withdraw.blade.php script section
        function selectGateway(gatewayId) {
            // Hide manual payment methods
            document.querySelectorAll('.method-card').forEach(card => {
                card.style.display = 'none';
            });

            // Show selected gateway
            const selectedGateway = document.querySelector(`#gateway${gatewayId}`).closest('.method-card');
            selectedGateway.style.display = 'block';
            selectedGateway.classList.add('selected');

            // Uncheck all manual payment methods
            document.querySelectorAll('input[name="payment_method_id"]').forEach(radio => {
                radio.checked = false;
            });

            // Check gateway radio
            document.querySelector(`#gateway${gatewayId}`).checked = true;
        }

        function selectMethod(methodId) {
            // Hide gateways
            document.querySelectorAll('[name="gateway_id"]').forEach(radio => {
                radio.checked = false;
            });

            // Show manual method
            document.querySelector(`#method${methodId}`).checked = true;

            // Show selected method
            const selectedMethod = document.querySelector(`#method${methodId}`).closest('.method-card');
            selectedMethod.classList.add('selected');
        }
        function selectMethod(methodId) {
            // Remove selected class from all method cards
            document.querySelectorAll('.method-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked method card
            const selectedCard = document.querySelector(`#method${methodId}`).closest('.method-card');
            selectedCard.classList.add('selected');

            // Check the radio button
            document.querySelector(`#method${methodId}`).checked = true;
        }

        function setAmount(amount) {
            const amountInput = document.getElementById('amount');
            amountInput.value = amount;

            // Remove selected class from all amount options
            document.querySelectorAll('.amount-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked amount option
            event.target.classList.add('selected');

            validateAmount();
        }

        function validateAmount() {
            const amountInput = document.getElementById('amount');
            const amount = parseFloat(amountInput.value);
            const feedback = document.getElementById('amountFeedback');

            if (!amount) {
                hideFeedback();
                return;
            }

            if (amount < {{ $minWithdrawal }}) {
                showFeedback('Amount must be at least RS. {{ number_format($minWithdrawal, 2) }}', 'danger');
            } else if (amount > {{ $maxWithdrawal }}) {
                showFeedback('Amount cannot exceed RS. {{ number_format($maxWithdrawal, 2) }}', 'danger');
            } else {
                showFeedback('Amount is valid. You will receive RS. ' + amount.toFixed(2), 'success');
            }
        }

        function showFeedback(message, type) {
            const feedback = document.getElementById('amountFeedback');
            feedback.className = `alert alert-${type}`;
            feedback.innerHTML = message;
            feedback.classList.remove('d-none');
        }

        function hideFeedback() {
            const feedback = document.getElementById('amountFeedback');
            feedback.classList.add('d-none');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Select first method by default
            const firstMethod = document.querySelector('input[name="payment_method_id"]');
            if (firstMethod) {
                selectMethod(firstMethod.value);
            }

            // Add event listeners
            document.getElementById('amount').addEventListener('input', validateAmount);

            // Form submission
            document.getElementById('withdrawForm').addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('amount').value);
                const terms = document.getElementById('terms').checked;

                if (!terms) {
                    e.preventDefault();
                    alert('Please accept the terms and conditions');
                    return;
                }

                if (amount < {{ $minWithdrawal }} || amount > {{ $maxWithdrawal }}) {
                    e.preventDefault();
                    alert('Please enter a valid amount');
                    return;
                }

                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endsection
