@extends('wholesaler.app')
@push('title')
    Payment Methods
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>💳 Payment Methods</h1>
                    <p>Manage your withdrawal payment methods</p>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">Your Payment Methods</h3>
                <a href="{{ route('wholesaler.payments.methods.create') }}" class="view-all-link">
                    <i class="fas fa-plus"></i> Add New Method
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($paymentMethods->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Payment Methods</h4>
                    <p class="text-muted">Add a payment method to receive withdrawals</p>
                    <a href="{{ route('wholesaler.payments.methods.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Method
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($paymentMethods as $method)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                @if($method->method_type == 'bank')
                                                    <i class="fas fa-university me-2 text-primary"></i>
                                                @else
                                                    <i class="fas fa-mobile-alt me-2 text-success"></i>
                                                @endif
                                                {{ ucfirst($method->method_type) }} Account
                                            </h5>
                                            @if($method->is_default)
                                                <span class="badge bg-success">Default</span>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('wholesaler.payments.methods.edit', $method->id) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                @if(!$method->is_default)
                                                    <li>
                                                        <form action="{{ route('wholesaler.payments.methods.destroy', $method->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this payment method?')">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="payment-method-details">
                                        <div class="mb-2">
                                            <strong>Account Name:</strong>
                                            <span class="text-muted">{{ $method->account_name }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Account Number:</strong>
                                            <span class="text-muted">{{ $method->account_number }}</span>
                                        </div>
                                        @if($method->method_type == 'bank')
                                            <div class="mb-2">
                                                <strong>Bank:</strong>
                                                <span class="text-muted">{{ $method->bank_name }}</span>
                                            </div>
                                            @if($method->branch)
                                                <div class="mb-2">
                                                    <strong>Branch:</strong>
                                                    <span class="text-muted">{{ $method->branch }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="mb-2">
                                                <strong>Provider:</strong>
                                                <span class="text-muted">{{ $method->mobile_provider }}</span>
                                            </div>
                                        @endif
                                        <div class="mb-2">
                                            <strong>Status:</strong>
                                            <span class="badge bg-{{ $method->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($method->status) }}
                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
