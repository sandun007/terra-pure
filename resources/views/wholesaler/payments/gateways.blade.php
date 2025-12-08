<!-- resources/views/wholesaler/payments/gateways.blade.php -->
@extends('wholesaler.layouts.app')

@section('title', 'Payment Gateways')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gateway-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .gateway-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .gateway-card.default {
            border-color: #007bff;
            background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
        }
        .gateway-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stripe-icon {
            background: linear-gradient(135deg, #635bff, #5a52d3);
            color: white;
        }
        .paypal-icon {
            background: linear-gradient(135deg, #003087, #009cde);
            color: white;
        }
        .jazzcash-icon {
            background: linear-gradient(135deg, #ff6b00, #ff8c00);
            color: white;
        }
        .easypaisa-icon {
            background: linear-gradient(135deg, #00a859, #007a43);
            color: white;
        }
        .upaisa-icon {
            background: linear-gradient(135deg, #1e4fa1, #163d7e);
            color: white;
        }
        .status-badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.75rem;
        }
        .test-btn {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.8rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Payment Gateways</h4>
                    <div class="btn-group">
                        <a href="{{ route('wholesaler.payments.gateways.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Gateway
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gateways Grid -->
        <div class="row">
            @if($gateways->count() > 0)
                @foreach($gateways as $gateway)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="gateway-card p-4 {{ $gateway->is_default ? 'default' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="gateway-icon {{ $gateway->gateway_type }}_icon">
                                    @switch($gateway->gateway_type)
                                        @case('stripe')
                                        @case('stripe_checkout')
                                            <i class="fab fa-stripe"></i>
                                            @break
                                        @case('paypal')
                                            <i class="fab fa-paypal"></i>
                                            @break
                                        @case('jazzcash')
                                            <i class="fas fa-mobile-alt"></i>
                                            @break
                                        @default
                                            <i class="fas fa-credit-card"></i>
                                    @endswitch
                                </div>
                                <div>
                                    @if($gateway->is_default)
                                        <span class="badge bg-success">Default</span>
                                    @endif
                                    <span class="badge bg-{{ $gateway->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($gateway->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5 class="mb-1">{{ $gateway->gateway_name }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-globe me-1"></i>
                                    {{ parse_url($gateway->api_url, PHP_URL_HOST) }}
                                </p>
                                <code class="text-dark small">Type: {{ $gateway->gateway_type }}</code>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Added {{ $gateway->created_at->diffForHumans() }}
                                </small>
                                <div class="btn-group">
                                    <a href="{{ route('wholesaler.payments.gateways.test', $gateway->id) }}"
                                       class="btn btn-outline-info btn-sm test-btn"
                                       title="Test Connection">
                                        <i class="fas fa-plug"></i> Test
                                    </a>
                                    @if(!$gateway->is_default)
                                        <form action="{{ route('wholesaler.payments.gateways.destroy', $gateway->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this gateway?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card">
                        <div class="empty-state text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-credit-card fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No Payment Gateways</h4>
                            <p class="text-muted mb-4">
                                Connect payment gateways to enable direct deposits and withdrawals.
                            </p>
                            <a href="{{ route('wholesaler.payments.gateways.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plug me-2"></i>Connect Your First Gateway
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Supported Gateways Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Supported Payment Gateways</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <div class="stripe-icon mx-auto mb-2">
                                    <i class="fab fa-stripe"></i>
                                </div>
                                <h6>Stripe</h6>
                                <p class="text-muted small">Credit/Debit Cards</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="paypal-icon mx-auto mb-2">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <h6>PayPal</h6>
                                <p class="text-muted small">International Payments</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="jazzcash-icon mx-auto mb-2">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <h6>JazzCash</h6>
                                <p class="text-muted small">Mobile Payments (PK)</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="easypaisa-icon mx-auto mb-2">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <h6>EasyPaisa</h6>
                                <p class="text-muted small">Mobile Payments (PK)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
