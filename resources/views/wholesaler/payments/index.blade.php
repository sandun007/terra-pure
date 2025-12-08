@extends('wholesaler.layouts.app')

@section('title', 'Payment Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .progress-bar {
            border-radius: 10px;
            height: 8px;
        }
        .transaction-item {
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .transaction-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .badge-pill {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.75rem;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Payment Dashboard</h4>
                    <div class="btn-group">
                        <a href="{{ route('wholesaler.payments.withdraw') }}" class="btn btn-success">
                            <i class="fas fa-money-bill-wave me-2"></i>Withdraw Funds
                        </a>
                        <a href="{{ route('wholesaler.payments.methods') }}" class="btn btn-outline-primary">
                            <i class="fas fa-credit-card me-2"></i>Payment Methods
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card payment-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="card-title text-white mb-1">RS. {{ number_format($availableBalance, 2) }}</h2>
                                <p class="text-white-50 mb-0">Available Balance</p>
                                <div class="mt-3">
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-arrow-up text-success me-1"></i>
                                Total Earnings: RS. {{ number_format($totalEarnings, 2) }}
                            </span>
                                    <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-clock text-warning me-1"></i>
                                Pending: RS. {{ number_format($pendingWithdrawals, 2) }}
                            </span>
                                    <span class="badge bg-light text-dark">
                                <i class="fas fa-arrow-down text-danger me-1"></i>
                                Withdrawn: RS. {{ number_format($totalWithdrawals, 2) }}
                            </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="fas fa-wallet display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary text-white me-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Earnings</h6>
                                <h4 class="mb-0">RS. {{ number_format($totalEarnings, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success text-white me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Available Balance</h6>
                                <h4 class="mb-0">RS. {{ number_format($availableBalance, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-warning text-white me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Pending Withdrawals</h6>
                                <h4 class="mb-0">RS. {{ number_format($pendingWithdrawals, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-info text-white me-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Withdrawn</h6>
                                <h4 class="mb-0">RS. {{ number_format($totalWithdrawals, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Quick Actions -->
        <div class="row">
            <!-- Recent Transactions -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Transactions</h5>
                        <a href="{{ route('wholesaler.payments.history') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentPayments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr class="transaction-item">
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if($payment->type == 'credit')
                                                            <i class="fas fa-arrow-down text-success me-2"></i>
                                                        @else
                                                            <i class="fas fa-arrow-up text-danger me-2"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0">{{ $payment->description }}</h6>
                                                        <small class="text-muted">{{ $payment->transaction_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                        <span class="badge bg-{{ $payment->type == 'credit' ? 'success' : 'danger' }}-light text-{{ $payment->type == 'credit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($payment->type) }}
                                        </span>
                                            </td>
                                            <td>
                                                <strong class="text-{{ $payment->type == 'credit' ? 'success' : 'danger' }}">
                                                    RS. {{ number_format($payment->amount, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                        <span class="badge badge-pill bg-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No transactions yet</h5>
                                <p class="text-muted">Your transaction history will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Summary -->
            <div class="col-xl-4 col-lg-5">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('wholesaler.payments.withdraw') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-money-bill-wave me-2"></i>Withdraw Funds
                            </a>
                            <a href="{{ route('wholesaler.payments.methods') }}" class="btn btn-outline-primary">
                                <i class="fas fa-credit-card me-2"></i>Manage Payment Methods
                            </a>
                            <a href="{{ route('wholesaler.payments.export') }}" class="btn btn-outline-info">
                                <i class="fas fa-download me-2"></i>Export Statements
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Summary</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalCompleted = $paymentSummary->where('status', 'completed')->sum('total_amount');
                            $totalPending = $paymentSummary->where('status', 'pending')->sum('total_amount');
                            $credits = $paymentSummary->where('type', 'credit')->sum('total_amount');
                            $debits = $paymentSummary->where('type', 'debit')->sum('total_amount');
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Credits</span>
                                <span class="text-success">RS. {{ number_format($credits, 2) }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: {{ $totalCompleted > 0 ? ($credits/$totalCompleted)*100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Debits</span>
                                <span class="text-danger">RS. {{ number_format($debits, 2) }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-danger" style="width: {{ $totalCompleted > 0 ? ($debits/$totalCompleted)*100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Completed</span>
                                <span class="text-primary">RS. {{ number_format($totalCompleted, 2) }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: {{ $totalCompleted > 0 ? 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Pending</span>
                                <span class="text-warning">RS. {{ number_format($totalPending, 2) }}</span>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" style="width: {{ $totalPending > 0 ? 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto refresh balance every 30 seconds
        setInterval(function() {
            // You can add AJAX call here to refresh balance
            console.log('Balance auto-refresh triggered');
        }, 30000);

        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endsection
