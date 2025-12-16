@extends('wholesaler.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Withdraw Funds</h1>
            <button class="button" style="--clr: #00ad54;" onclick="window.location='{{ route('payments.index') }}'">
                <span class="button-decor"></span>
                <div class="button-content">
                    <div class="button__icon">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <span class="button__text">Back to Payments</span>
                </div>
            </button>
        </div>

        <!-- Balance Summary -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Available Balance</h5>
                        <h2 class="text-success">RS. {{ number_format($availableBalance, 2) }}</h2>
                        <p class="text-muted">Minimum withdrawal amount: RS. {{ number_format($minWithdrawal, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <div class="container">
                            <div class="left-side">
                                <div class="card">
                                    <div class="card-line"></div>
                                    <div class="buttons"></div>
                                </div>
                                <div class="post">
                                    <div class="post-line"></div>
                                    <div class="screen">
                                        <div class="dollar">$</div>
                                    </div>
                                    <div class="numbers"></div>
                                    <div class="numbers-line2"></div>
                                </div>
                            </div>
                            <div class="right-side">
                                <div class="new">Withdraw Now</div>
                                <svg viewBox="0 0 451.846 451.847" height="512" width="512" xmlns="http://www.w3.org/2000/svg" class="arrow">
                                    <path fill="#cfcfcf" data-old_color="#000000" class="active-path" data-original="#000000" d="M345.441 248.292L151.154 442.573c-12.359 12.365-32.397 12.365-44.75 0-12.354-12.354-12.354-32.391 0-44.744L278.318 225.92 106.409 54.017c-12.354-12.359-12.354-32.394 0-44.748 12.354-12.359 32.391-12.359 44.75 0l194.287 194.284c6.177 6.18 9.262 14.271 9.262 22.366 0 8.099-3.091 16.196-9.267 22.373z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">New Withdrawal Request</h6>
                    </div>
                    <div class="card-body">
                        <form id="withdrawForm" action="{{ route('payments.withdraw.process') }}" method="POST">
                            @csrf

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount">Amount (RS.) *</label>
                                <input type="number"
                                       class="form-control"
                                       id="amount"
                                       name="amount"
                                       required
                                       min="{{ $minWithdrawal }}"
                                       max="{{ $maxWithdrawal }}"
                                       step="0.01"
                                       placeholder="Enter withdrawal amount">
                                <small class="form-text text-muted">
                                    Available: RS. {{ number_format($availableBalance, 2) }}
                                </small>
                                <div class="invalid-feedback" id="amountError"></div>
                            </div>

                            <!-- Payment Method -->
                            <div class="form-group">
                                <label>Select Payment Method *</label>
                                @forelse($paymentMethods as $method)
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio"
                                               id="method{{ $method->id }}"
                                               name="payment_method_id"
                                               value="{{ $method->id }}"
                                               class="custom-control-input"
                                               {{ $method->is_default ? 'checked' : '' }}
                                               required>
                                        <label class="custom-control-label d-flex align-items-center" for="method{{ $method->id }}">
                                            <i class="{{ $method->icon_class }} mr-2"></i>
                                            <div>
                                                <strong>{{ $method->display_name }}</strong>
                                                @if($method->is_default)
                                                    <span class="badge badge-success ml-2">Default</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @empty
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        No payment methods found.
                                        <a href="{{ route('payments.methods') }}" class="alert-link">Add a payment method first</a>.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Notes -->
                            <div class="form-group">
                                <label for="notes">Additional Notes (Optional)</label>
                                <textarea class="form-control"
                                          id="notes"
                                          name="notes"
                                          rows="3"
                                          placeholder="Add any notes about this withdrawal"></textarea>
                            </div>

                            <!-- Terms -->
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree that withdrawals take 24-48 hours to process
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" {{ $paymentMethods->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane mr-2"></i>Submit Withdrawal Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Withdrawal Limits -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Withdrawal Limits</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Minimum Withdrawal
                                <span class="badge badge-primary badge-pill">RS. {{ number_format($minWithdrawal, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Processing Time
                                <span class="badge badge-info badge-pill">24-48 Hours</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Transaction Fee
                                <span class="badge badge-success badge-pill">Free</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Payment Method Card -->
                @if($paymentMethods->isNotEmpty())
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="card" style="--primary: #00ad54;">
                                <div class="card__info">
                                    <div class="card__logo">{{ $paymentMethods->firstWhere('is_default', true)->bank_name ?? 'Bank' }}</div>
                                    <div class="card__chip">
                                        <svg class="card__chip-lines" role="img" width="20px" height="20px" viewBox="0 0 100 100" aria-label="Chip">
                                            <g opacity="0.8">
                                                <polyline points="0,50 35,50" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="0,20 20,20 35,35" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="50,0 50,35" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="65,35 80,20 100,20" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="100,50 65,50" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="35,35 65,35 65,65 35,65 35,35" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="0,80 20,80 35,65" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="50,100 50,65" fill="none" stroke="#000" stroke-width="2"></polyline>
                                                <polyline points="65,65 80,80 100,80" fill="none" stroke="#000" stroke-width="2"></polyline>
                                            </g>
                                        </svg>
                                        <div class="card__chip-texture"></div>
                                    </div>
                                    <div class="card__type">debit</div>
                                    <div class="card__number">
                                        <span class="card__digit-group">XXXX</span>
                                        <span class="card__digit-group">XXXX</span>
                                        <span class="card__digit-group">XXXX</span>
                                        <span class="card__digit-group">{{ substr($paymentMethods->firstWhere('is_default', true)->account_number ?? '0000', -4) }}</span>
                                    </div>
                                    <div class="card__valid-thru" aria-label="Valid thru">Valid<br>thru</div>
                                    <div class="card__exp-date"><time datetime="2038-01">01/38</time></div>
                                    <div class="card__name" aria-label="Account Name">{{ $paymentMethods->firstWhere('is_default', true)->account_name ?? 'Your Name' }}</div>
                                    <div class="card__vendor" role="img" aria-labelledby="card-vendor">
                                        <span id="card-vendor" class="card__vendor-sr">Mastercard</span>
                                    </div>
                                    <div class="card__texture"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('withdrawForm');
            const amountInput = document.getElementById('amount');
            const submitBtn = document.getElementById('submitBtn');
            const availableBalance = {{ $availableBalance }};
            const minWithdrawal = {{ $minWithdrawal }};

            amountInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;

                if (amount > availableBalance) {
                    showError('Amount exceeds available balance');
                    submitBtn.disabled = true;
                } else if (amount < minWithdrawal) {
                    showError(`Minimum withdrawal amount is RS. ${minWithdrawal}`);
                    submitBtn.disabled = true;
                } else {
                    clearError();
                    submitBtn.disabled = false;
                }
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const amount = parseFloat(amountInput.value) || 0;

                if (!validateAmount(amount)) {
                    return;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                submitBtn.disabled = true;

                // Submit form
                form.submit();
            });

            function validateAmount(amount) {
                if (amount < minWithdrawal) {
                    showError(`Minimum withdrawal amount is RS. ${minWithdrawal}`);
                    return false;
                }

                if (amount > availableBalance) {
                    showError('Insufficient balance');
                    return false;
                }

                return true;
            }

            function showError(message) {
                const errorDiv = document.getElementById('amountError');
                errorDiv.textContent = message;
                amountInput.classList.add('is-invalid');
            }

            function clearError() {
                const errorDiv = document.getElementById('amountError');
                errorDiv.textContent = '';
                amountInput.classList.remove('is-invalid');
            }
        });
    </script>

    <!-- Styles -->
    <style>
        .button {
            text-decoration: none;
            line-height: 1;
            border-radius: 1.5rem;
            overflow: hidden;
            position: relative;
            box-shadow: 10px 10px 20px rgba(0,0,0,.05);
            background-color: #fff;
            color: #121212;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .button-decor {
            position: absolute;
            inset: 0;
            background-color: var(--clr);
            transform: translateX(-100%);
            transition: transform .3s;
            z-index: 0;
        }

        .button-content {
            display: flex;
            align-items: center;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .button__icon {
            width: 48px;
            height: 40px;
            background-color: var(--clr);
            display: grid;
            place-items: center;
        }

        .button__text {
            display: inline-block;
            transition: color .2s;
            padding: 2px 1.5rem 2px;
            padding-left: .75rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .button:hover .button__text {
            color: #fff;
        }

        .button:hover .button-decor {
            transform: translate(0);
        }

        /* Card Styles */
        .card,
        .card__chip {
            overflow: hidden;
            position: relative;
        }

        .card,
        .card__chip-texture,
        .card__texture {
            animation-duration: 3s;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }

        .card {
            animation-name: rotate_500;
            background-color: var(--primary);
            background-image: radial-gradient(circle at 100% 0%,hsla(0,0%,100%,0.08) 29.5%,hsla(0,0%,100%,0) 30%),
            radial-gradient(circle at 100% 0%,hsla(0,0%,100%,0.08) 39.5%,hsla(0,0%,100%,0) 40%),
            radial-gradient(circle at 100% 0%,hsla(0,0%,100%,0.08) 49.5%,hsla(0,0%,100%,0) 50%);
            border-radius: 0.5em;
            box-shadow: 0 0 0 hsl(0,0%,80%),
            0 0 0 hsl(0,0%,100%),
            -0.2rem 0 0.75rem 0 hsla(0,0%,0%,0.3);
            color: hsl(0,0%,100%);
            width: 10.3em;
            height: 6.8em;
            transform: translate3d(0,0,0);
        }

        .card__info,
        .card__chip-texture,
        .card__texture {
            position: absolute;
        }

        .card__chip-texture,
        .card__texture {
            animation-name: texture;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
        }

        .card__info {
            font: 0.75em/1 "DM Sans", sans-serif;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            padding: 0.75rem;
            inset: 0;
        }

        .card__logo,
        .card__number {
            width: 100%;
        }

        .card__logo {
            font-weight: bold;
            font-style: italic;
        }

        .card__chip {
            background-image: linear-gradient(hsl(0,0%,70%),hsl(0,0%,80%));
            border-radius: 0.2rem;
            box-shadow: 0 0 0 0.05rem hsla(0,0%,0%,0.5) inset;
            width: 1.25rem;
            height: 1.25rem;
            transform: translate3d(0,0,0);
        }

        .card__chip-lines {
            width: 100%;
            height: auto;
        }

        .card__chip-texture {
            background-image: linear-gradient(-80deg,hsla(0,0%,100%,0),hsla(0,0%,100%,0.6) 48% 52%,hsla(0,0%,100%,0));
        }

        .card__type {
            align-self: flex-end;
            margin-left: auto;
        }

        .card__digit-group,
        .card__exp-date,
        .card__name {
            background: linear-gradient(hsl(0,0%,100%),hsl(0,0%,85%) 15% 55%,hsl(0,0%,70%) 70%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: "Courier Prime", monospace;
            filter: drop-shadow(0 0.05rem hsla(0,0%,0%,0.3));
        }

        .card__number {
            font-size: 0.8rem;
            display: flex;
            justify-content: space-between;
        }

        .card__valid-thru,
        .card__name {
            text-transform: uppercase;
        }

        .card__valid-thru,
        .card__exp-date {
            margin-bottom: 0.25rem;
            width: 50%;
        }

        .card__valid-thru {
            font-size: 0.3rem;
            padding-right: 0.25rem;
            text-align: right;
        }

        .card__exp-date,
        .card__name {
            font-size: 0.6rem;
        }

        .card__exp-date {
            padding-left: 0.25rem;
        }

        .card__name {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            width: 6.25rem;
        }

        .card__vendor,
        .card__vendor:before,
        .card__vendor:after {
            position: absolute;
        }

        .card__vendor {
            right: 0.375rem;
            bottom: 0.375rem;
            width: 2.55rem;
            height: 1.5rem;
        }

        .card__vendor:before,
        .card__vendor:after {
            border-radius: 50%;
            content: "";
            display: block;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
        }

        .card__vendor:before {
            background-color: #e71d1a;
            left: 0;
        }

        .card__vendor:after {
            background-color: #fa5e03;
            box-shadow: -1.05rem 0 0 #f59d1a inset;
            right: 0;
        }

        .card__vendor-sr {
            clip: rect(1px,1px,1px,1px);
            overflow: hidden;
            position: absolute;
            width: 1px;
            height: 1px;
        }

        .card__texture {
            animation-name: texture;
            background-image: linear-gradient(-80deg,hsla(0,0%,100%,0.3) 25%,hsla(0,0%,100%,0) 45%);
        }

        /* Withdrawal Card Animation */
        .container {
            background-color: #ffffff;
            display: flex;
            width: 460px;
            height: 120px;
            position: relative;
            border-radius: 6px;
            transition: 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.03);
            width: 220px;
        }

        .container:hover .left-side {
            width: 100%;
        }

        .left-side {
            background-color: #5de2a3;
            width: 130px;
            height: 120px;
            border-radius: 4px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
            flex-shrink: 0;
            overflow: hidden;
        }

        .right-side {
            width: calc(100% - 130px);
            display: flex;
            align-items: center;
            overflow: hidden;
            cursor: pointer;
            justify-content: space-between;
            white-space: nowrap;
            transition: 0.3s;
        }

        .right-side:hover {
            background-color: #f9f7f9;
        }

        .arrow {
            width: 20px;
            height: 20px;
            margin-right: 20px;
        }

        .new {
            font-size: 23px;
            font-family: "Lexend Deca", sans-serif;
            margin-left: 20px;
        }

        .card {
            width: 70px;
            height: 46px;
            background-color: #c7ffbc;
            border-radius: 6px;
            position: absolute;
            display: flex;
            z-index: 10;
            flex-direction: column;
            align-items: center;
            -webkit-box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
            -moz-box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
            -webkit-box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
        }

        .card-line {
            width: 65px;
            height: 13px;
            background-color: #80ea69;
            border-radius: 2px;
            margin-top: 7px;
        }

        .buttons {
            width: 8px;
            height: 8px;
            background-color: #379e1f;
            box-shadow: 0 -10px 0 0 #26850e, 0 10px 0 0 #56be3e;
            border-radius: 50%;
            margin-top: 5px;
            transform: rotate(90deg);
            margin: 10px 0 0 -30px;
        }

        .container:hover .card {
            animation: slide-top 1.2s cubic-bezier(0.645, 0.045, 0.355, 1) both;
        }

        .container:hover .post {
            animation: slide-post 1s cubic-bezier(0.165, 0.84, 0.44, 1) both;
        }

        @keyframes slide-top {
            0% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            50% {
                -webkit-transform: translateY(-70px) rotate(90deg);
                transform: translateY(-70px) rotate(90deg);
            }

            60% {
                -webkit-transform: translateY(-70px) rotate(90deg);
                transform: translateY(-70px) rotate(90deg);
            }

            100% {
                -webkit-transform: translateY(-8px) rotate(90deg);
                transform: translateY(-8px) rotate(90deg);
            }
        }

        .post {
            width: 63px;
            height: 75px;
            background-color: #dddde0;
            position: absolute;
            z-index: 11;
            bottom: 10px;
            top: 120px;
            border-radius: 6px;
            overflow: hidden;
        }

        .post-line {
            width: 47px;
            height: 9px;
            background-color: #545354;
            position: absolute;
            border-radius: 0px 0px 3px 3px;
            right: 8px;
            top: 8px;
        }

        .post-line:before {
            content: "";
            position: absolute;
            width: 47px;
            height: 9px;
            background-color: #757375;
            top: -8px;
        }

        .screen {
            width: 47px;
            height: 23px;
            background-color: #ffffff;
            position: absolute;
            top: 22px;
            right: 8px;
            border-radius: 3px;
        }

        .numbers {
            width: 12px;
            height: 12px;
            background-color: #838183;
            box-shadow: 0 -18px 0 0 #838183, 0 18px 0 0 #838183;
            border-radius: 2px;
            position: absolute;
            transform: rotate(90deg);
            left: 25px;
            top: 52px;
        }

        .numbers-line2 {
            width: 12px;
            height: 12px;
            background-color: #aaa9ab;
            box-shadow: 0 -18px 0 0 #aaa9ab, 0 18px 0 0 #aaa9ab;
            border-radius: 2px;
            position: absolute;
            transform: rotate(90deg);
            left: 25px;
            top: 68px;
        }

        @keyframes slide-post {
            50% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            100% {
                -webkit-transform: translateY(-70px);
                transform: translateY(-70px);
            }
        }

        .dollar {
            position: absolute;
            font-size: 16px;
            font-family: "Lexend Deca", sans-serif;
            width: 100%;
            left: 0;
            top: 0;
            color: #4b953b;
            text-align: center;
        }

        .container:hover .dollar {
            animation: fade-in-fwd 0.3s 1s backwards;
        }

        @keyframes fade-in-fwd {
            0% {
                opacity: 0;
                transform: translateY(-5px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation */
        @keyframes rotate_500 {
            from,
            to {
                animation-timing-function: ease-in;
                box-shadow: 0 0 0 hsl(0,0%,80%),
                0.1rem 0 0 hsl(0,0%,100%),
                -0.2rem 0 0.75rem 0 hsla(0,0%,0%,0.3);
                transform: rotateY(-10deg);
            }

            25%,
            75% {
                animation-timing-function: ease-out;
                box-shadow: 0 0 0 hsl(0,0%,80%),
                0 0 0 hsl(0,0%,100%),
                -0.25rem -0.05rem 1rem 0.15rem hsla(0,0%,0%,0.3);
                transform: rotateY(0deg);
            }

            50% {
                animation-timing-function: ease-in;
                box-shadow: -0.1rem 0 0 hsl(0,0%,80%),
                0 0 0 hsl(0,0%,100%),
                -0.3rem -0.1rem 1.5rem 0.3rem hsla(0,0%,0%,0.3);
                transform: rotateY(10deg);
            }
        }

        @keyframes texture {
            from,
            to {
                transform: translate3d(0,0,0);
            }

            50% {
                transform: translate3d(-50%,0,0);
            }
        }
    </style>
@endsection
