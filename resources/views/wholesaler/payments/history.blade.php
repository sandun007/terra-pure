@extends('wholesaler.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Payment History</h1>
            <div class="d-flex">
                <a href="{{ route('payments.export.csv') }}" class="btn btn-outline-success mr-2">
                    <i class="fas fa-file-export mr-1"></i> Export CSV
                </a>
                <button class="button" style="--clr: #00ad54;" onclick="window.location='{{ route('payments.index') }}'">
                    <span class="button-decor"></span>
                    <div class="button-content">
                        <div class="button__icon">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <span class="button__text">Back</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('payments.history') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type">Transaction Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="">All Types</option>
                                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit (Income)</option>
                                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit (Withdrawal)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('payments.history') }}" class="btn btn-secondary">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Payment Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <code>{{ $payment->transaction_id }}</code>
                                </td>
                                <td>
                                <span class="badge {{ $payment->type === 'credit' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($payment->type) }}
                                </span>
                                </td>
                                <td class="font-weight-bold {{ $payment->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $payment->type === 'credit' ? '+' : '-' }} RS. {{ number_format($payment->amount, 2) }}
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                <td>
                                <span class="badge {{ $payment->statusBadgeClass }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                                </td>
                                <td>
                                    <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($payment->type == 'debit' && $payment->status == 'pending')
                                        <form action="{{ route('payments.withdraw.cancel', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this withdrawal?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No transactions found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $payments->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

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

        .badge-success {
            background-color: #00ad54 !important;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107 !important;
            color: #212529;
        }

        .badge-danger {
            background-color: #dc3545 !important;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d !important;
            color: white;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>
@endsection
