@extends('wholesaler.layouts.app')

@section('title', 'Payment Methods')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .method-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .method-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .method-card.default {
            border-color: #007bff;
            background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
        }
        .method-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .bank-icon {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        .mobile-icon {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }
        .card-icon {
            background: linear-gradient(135deg, #6f42c1, #5a2d9c);
            color: white;
        }
        .action-buttons .btn {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }
        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Payment Methods</h4>
                    <div class="btn-group">
                        <a href="{{ route('wholesaler.payments.methods.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Payment Method
                        </a>
                        <a href="{{ route('wholesaler.payments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Grid -->
        <div class="row">
            @if($paymentMethods->count() > 0)
                @foreach($paymentMethods as $method)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="method-card p-4 {{ $method->is_default ? 'default' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="method-icon {{ $method->method_type == 'bank' ? 'bank-icon' : 'mobile-icon' }}">
                                    <i class="fas fa-{{ $method->method_type == 'bank' ? 'university' : 'mobile-alt' }}"></i>
                                </div>
                                <div>
                                    @if($method->is_default)
                                        <span class="badge bg-success">Default</span>
                                    @endif
                                    <span class="badge bg-{{ $method->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($method->status) }}
                        </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5 class="mb-1">
                                    {{ $method->method_type == 'bank' ? $method->bank_name : $method->mobile_provider }}
                                </h5>
                                <p class="text-muted mb-1">{{ $method->account_name }}</p>
                                <code class="text-dark">{{ $method->account_number }}</code>

                                @if($method->method_type == 'bank' && $method->branch)
                                    <p class="text-muted mb-0 small">Branch: {{ $method->branch }}</p>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Added {{ $method->created_at->diffForHumans() }}
                                </small>
                                <div class="action-buttons">
                                    <div class="btn-group">
                                        <a href="{{ route('wholesaler.payments.methods.edit', $method->id) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$method->is_default)
                                            <form action="{{ route('wholesaler.payments.methods.destroy', $method->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this payment method?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card">
                        <div class="empty-state">
                            <div class="mb-4">
                                <i class="fas fa-credit-card fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No Payment Methods</h4>
                            <p class="text-muted mb-4">
                                You haven't added any payment methods yet. Add a payment method to withdraw your earnings.
                            </p>
                            <a href="{{ route('wholesaler.payments.methods.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Add Your First Payment Method
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Important Notes -->
        @if($paymentMethods->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Important Notes</h6>
                        <ul class="mb-0">
                            <li>Your default payment method will be used for all withdrawals</li>
                            <li>Ensure your account details are accurate to avoid payment delays</li>
                            <li>You can have multiple payment methods but only one default</li>
                            <li>Inactive payment methods cannot be used for withdrawals</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const methodCards = document.querySelectorAll('.method-card');
            methodCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endsection
