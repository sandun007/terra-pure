@extends('wholesaler.app')
@push('title')
    Brands Management
@endpush

@push('css')
    <style>
        .brand-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }

        .brand-image {
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .brand-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-count {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>🏷️ Brands Management</h1>
                    <p>Manage your product brands and categories</p>
                </div>
                <div class="business-badge">
                    <span class="badge">{{ $brands->count() }} Brands</span>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="section-header">
                <h3 class="section-title">All Brands</h3>
                <a href="{{ route('wholesaler.brands.create') }}" class="view-all-link">
                    <i class="fas fa-plus"></i> Add New Brand
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

            @if($brands->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Brands Found</h4>
                    <p class="text-muted">Start by creating your first brand</p>
                    <a href="{{ route('wholesaler.brands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Brand
                    </a>
                </div>
            @else
                <div class="row">
                    @foreach($brands as $brand)
                        <div class="col-md-4 mb-4">
                            <div class="brand-card">
                                <div class="brand-image">
                                    @if($brand->img)
                                        <img src="{{ asset('storage/' . $brand->img) }}" alt="{{ $brand->name }}">
                                    @else
                                        <i class="fas fa-tag"></i>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $brand->name }}</h5>
                                        <span class="product-count">
                                {{ \App\Models\Product::where('brand', $brand->name)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }} products
                            </span>
                                    </div>
                                    <p class="text-muted small mb-3">Code: {{ $brand->code }}</p>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('wholesaler.brands.edit', $brand->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('wholesaler.brands.destroy', $brand->id) }}" method="POST" class="flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                                    onclick="return confirm('Are you sure you want to delete this brand?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
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
