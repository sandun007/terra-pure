@extends('wholesaler.app')
@push('title')
    Payments & Earnings
@endpush

@push('css')
    <style>
        .earning-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .payment-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed { background: #fee2e2; color: #991b1b; }

        .transaction-type {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
        }

        .type-credit { background: #d1fae5; color: #065f46; }
        .type-debit { background: #fee2e2; color: #991b1b; }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>💰 Payments & Earnings</h1>
                    <p>Manage your earnings and withdrawal requests</p>
                </div>
            </div>
        </div>

        <!-- Earnings Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="earning-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Earnings</h6>
                            <h3 class="mb-0">RS. {{ number_format($totalEarnings, 2) }}</h3>
                        </div>
                        <i class="fas fa-wallet fa-2x text-white-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Available for withdrawal</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wholesale-stat-card">
                    <div class="wholesale-stat-header">
                        <div class="wholesale-stat-title">Pending Withdrawals</div>
                        <div class="wholesale-stat-icon icon-orders">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="wholesale-stat-value">RS. {{ number_format($pendingWithdrawals, 2) }}</div>
                    <div class="wholesale-stat-change">
                        <i class="fas fa-info-circle"></i>
                        <span>Awaiting processing</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wholesale-stat-card">
                    <div class="wholesale-stat-header">
                        <div class="wholesale-stat-title">Net Balance</div>
                        <div class="wholesale-stat-icon icon-revenue">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="wholesale-stat-value">RS. {{ number_format($totalEarnings - $pendingWithdrawals, 2) }}</div>
                    <div class="wholesale-stat-change">
                        <i class="fas fa-check-circle"></i>
                        <span>Available balance</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">Transaction History</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('wholesaler.payments.withdraw') }}" class="view-all-link">
                        <i class="fas fa-money-bill-wave me-1"></i> Withdraw Funds
                    </a>
                    <a href="{{ route('wholesaler.payments.methods') }}" class="view-all-link">
                        <i class="fas fa-credit-card me-1"></i> Payment Methods
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($payments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Transactions Yet</h4>
                    <p class="text-muted">Your transaction history will appear here</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->transaction_id }}</strong></td>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>
                            <span class="transaction-type type-{{ $payment->type }}">
                                {{ strtoupper($payment->type) }}
                            </span>
                                </td>
                                <td>
                                    <strong class="{{ $payment->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                        RS. {{ number_format($payment->amount, 2) }}
                                    </strong>
                                </td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>
                            <span class="payment-status status-{{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                                </td>
                                <td>{{ $payment->description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
