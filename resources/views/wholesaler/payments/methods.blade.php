@extends('wholesaler.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Payment Methods</h1>
            <div class="d-flex">
                <button class="button mr-2" style="--clr: #00ad54;" onclick="window.location='{{ route('payments.methods.create') }}'">
                    <span class="button-decor"></span>
                    <div class="button-content">
                        <div class="button__icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="button__text">Add New</span>
                    </div>
                </button>
                <button class="button" style="--clr: #6c757d;" onclick="window.location='{{ route('payments.index') }}'">
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

        <!-- Payment Methods -->
        <div class="row">
            @forelse($paymentMethods as $method)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        <i class="{{ $method->icon_class }} mr-2"></i>
                                        {{ ucfirst($method->method_type) }}
                                    </h5>
                                    @if($method->is_default)
                                        <span class="badge badge-success">Default</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('payments.methods.edit', $method->id) }}">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                        @if(!$method->is_default)
                                            <form action="{{ route('payments.methods.destroy', $method->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash mr-2"></i>Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Account Name</small>
                                <p class="mb-1 font-weight-bold">{{ $method->account_name }}</p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Account Number</small>
                                <p class="mb-1 font-weight-bold">{{ $method->formatted_account_number }}</p>
                            </div>

                            @if($method->bank_name)
                                <div class="mb-3">
                                    <small class="text-muted">Bank Name</small>
                                    <p class="mb-1">{{ $method->bank_name }}</p>
                                </div>
                            @endif

                            @if($method->mobile_provider)
                                <div class="mb-3">
                                    <small class="text-muted">Mobile Provider</small>
                                    <p class="mb-1">{{ $method->mobile_provider }}</p>
                                </div>
                            @endif

                            @if($method->expiry_date)
                                <div class="mb-3">
                                    <small class="text-muted">Expiry Date</small>
                                    <p class="mb-1">{{ $method->expiry_date->format('m/Y') }}</p>
                                </div>
                            @endif

                            <div class="mt-3">
                        <span class="badge {{ $method->status == 'active' ? 'badge-success' : 'badge-secondary' }}">
                            {{ ucfirst($method->status) }}
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No payment methods found</h5>
                            <p class="text-muted mb-4">Add a payment method to start receiving payments</p>
                            <a href="{{ route('payments.methods.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Payment Method
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
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

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .dropdown-menu {
            min-width: 150px;
        }
    </style>
@endsection
