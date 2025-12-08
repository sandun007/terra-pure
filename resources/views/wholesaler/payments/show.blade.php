@extends('wholesaler.layouts.app')

@section('title', 'Payment Details')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .transaction-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0;
            color: white;
            padding: 2rem;
        }
        .detail-item {
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .status-badge {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .amount-display {
            font-size: 2.5rem;
            font-weight: 700;
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
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
        }
        .timeline-item:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1.5rem;
            bottom: -1.5rem;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item:last-child:after {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Transaction Details</h4>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details Card -->
                <div class="card detail-card">
                    <!-- Header Section -->
                    <div class="transaction-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h2 class="mb-1">
                                    @if($payment->type == 'credit')
                                        <i class="fas fa-arrow-down me-2"></i>Credit
                                    @else
                                        <i class="fas fa-arrow-up me-2"></i>Debit
                                    @endif
                                </h2>
                                <p class="mb-0 opacity-75">{{ $payment->description }}</p>
                            </div>
                            <div class="col-4 text-end">
                        <span class="status-badge bg-light text-dark">
                            {{ ucfirst($payment->status) }}
                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Amount Display -->
                        <div class="text-center py-4">
                            <div class="amount-display text-{{ $payment->type == 'credit' ? 'success' : 'danger' }}">
                                {{ $payment->type == 'credit' ? '+' : '-' }}RS. {{ number_format($payment->amount, 2) }}
                            </div>
                            <p class="text-muted">Transaction Amount</p>
                        </div>

                        <!-- Details Grid -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Transaction ID</strong>
                                    <div class="text-muted">
                                        <code>{{ $payment->transaction_id }}</code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Date & Time</strong>
                                    <div class="text-muted">
                                        {{ $payment->created_at->format('F d, Y') }}<br>
                                        {{ $payment->created_at->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Payment Type</strong>
                                    <div class="text-muted">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Transaction Type</strong>
                                    <div class="text-muted">
                                <span class="badge bg-{{ $payment->type == 'credit' ? 'success' : 'danger' }}-light text-{{ $payment->type == 'credit' ? 'success' : 'danger' }}">
                                    {{ ucfirst($payment->type) }}
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($payment->payment_method)
                            <div class="detail-item">
                                <strong>Payment Method</strong>
                                <div class="text-muted">{{ $payment->payment_method }}</div>
                            </div>
                        @endif

                        @if($payment->notes)
                            <div class="detail-item">
                                <strong>Notes</strong>
                                <div class="text-muted">{{ $payment->notes }}</div>
                            </div>
                        @endif

                        <!-- Timeline -->
                        <div class="detail-item">
                            <strong>Transaction Timeline</strong>
                            <div class="mt-3">
                                <div class="timeline-item">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle p-2 me-3">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                        <div>
                                            <strong>Transaction Created</strong>
                                            <div class="text-muted small">
                                                {{ $payment->created_at->format('M d, Y h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($payment->processed_at)
                                    <div class="timeline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-circle p-2 me-3">
                                                <i class="fas fa-cog text-white"></i>
                                            </div>
                                            <div>
                                                <strong>Processing Started</strong>
                                                <div class="text-muted small">
                                                    {{ $payment->processed_at->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($payment->completed_at)
                                    <div class="timeline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle p-2 me-3">
                                                <i class="fas fa-flag-checkered text-white"></i>
                                            </div>
                                            <div>
                                                <strong>Completed</strong>
                                                <div class="text-muted small">
                                                    {{ $payment->completed_at->format('M d, Y h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                @if($payment->type == 'debit' && $payment->status == 'pending')
                                    <form action="{{ route('wholesaler.payments.cancel-withdraw', $payment->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to cancel this withdrawal?')">
                                            <i class="fas fa-times me-2"></i>Cancel Withdrawal
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('wholesaler.payments.history') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-list me-2"></i>View All
                                </a>
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="fas fa-print me-2"></i>Print Receipt
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-question-circle me-2"></i>Need Help?</h6>
                            <p class="mb-0">
                                If you have any questions about this transaction, please contact our support team
                                with your transaction ID: <strong>{{ $payment->transaction_id }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Print styling
        const originalTitle = document.title;
        window.addEventListener('beforeprint', function() {
            document.title = 'Transaction Receipt - {{ $payment->transaction_id }}';
        });

        window.addEventListener('afterprint', function() {
            document.title = originalTitle;
        });
    </script>
@endsection
