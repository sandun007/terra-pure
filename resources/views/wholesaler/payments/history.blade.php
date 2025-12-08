@extends('wholesaler.layouts.app')

@section('title', 'Payment History')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .filter-card {
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        .transaction-type-badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.75rem;
        }
        .amount-cell {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .export-btn {
            border-radius: 25px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .page-title-box {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-group .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Payment History</h4>
                    <div class="btn-group">
                        <a href="{{ route('wholesaler.payments.export', request()->all()) }}"
                           class="btn btn-success export-btn">
                            <i class="fas fa-file-export me-2"></i>Export CSV
                        </a>
                        <a href="{{ route('wholesaler.payments.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <form action="{{ route('wholesaler.payments.history') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Transaction Type</label>
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Transaction History</h5>
                    </div>
                    <div class="card-body">
                        @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Transaction ID</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->created_at->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $payment->transaction_id }}</code>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $payment->description }}</h6>
                                                    @if($payment->notes)
                                                        <small class="text-muted">{{ Str::limit($payment->notes, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                        <span class="transaction-type-badge
                                            {{ $payment->type == 'credit' ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                                            <i class="fas fa-arrow-{{ $payment->type == 'credit' ? 'down' : 'up' }} me-1"></i>
                                            {{ ucfirst($payment->type) }}
                                        </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $payment->payment_method ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                        <span class="amount-cell text-{{ $payment->type == 'credit' ? 'success' : 'danger' }}">
                                            {{ $payment->type == 'credit' ? '+' : '-' }}RS. {{ number_format($payment->amount, 2) }}
                                        </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'completed' => 'success',
                                                        'failed' => 'danger',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                @endphp
                                                <span class="status-badge bg-{{ $statusColors[$payment->status] }}-light text-{{ $statusColors[$payment->status] }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('wholesaler.payments.show', $payment->id) }}"
                                                       class="btn btn-outline-primary"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($payment->type == 'debit' && $payment->status == 'pending')
                                                        <form action="{{ route('wholesaler.payments.cancel-withdraw', $payment->id) }}"
                                                              method="POST"
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    title="Cancel Withdrawal"
                                                                    onclick="return confirm('Are you sure you want to cancel this withdrawal?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
                                </div>
                                <div>
                                    {{ $payments->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-receipt fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted">No transactions found</h4>
                                <p class="text-muted mb-4">No transactions match your current filters.</p>
                                <a href="{{ route('wholesaler.payments.history') }}" class="btn btn-primary">
                                    <i class="fas fa-refresh me-2"></i>Clear Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-submit form on filter change
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('select[name="type"], select[name="status"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });

            // Add loading state to export button
            const exportBtn = document.querySelector('.export-btn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-file-export me-2"></i>Export CSV';
                    }, 3000);
                });
            }
        });
    </script>
@endsection
