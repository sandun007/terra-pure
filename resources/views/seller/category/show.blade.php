@extends('seller.layout.app')
@push('title')
    {{ $category->name }} - Category Details
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .category-details-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .category-image-container {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .category-image {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #475569;
            font-weight: 500;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            border-color: #10b981;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background: #f8fafc;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .empty-state i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: none;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold d-flex align-items-center">
                    <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">category</span>
                    Category Details
                </h3>
                <p class="mb-0 opacity-90 d-flex align-items-center">
                    <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">info</span>
                    View complete information about {{ $category->name }}
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('seller.category.index') }}" class="btn btn-outline-light d-flex align-items-center">
                    <span class="material-icons-outlined me-2">arrow_back</span>
                    Back to Categories
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Category Details -->
        <div class="col-lg-5">
            <div class="category-details-card">
                <div class="category-image-container">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             alt="{{ $category->name }}"
                             class="category-image"
                             onerror="this.src='{{ asset('images/default-category.png') }}'">
                    @else
                        <span class="material-icons-outlined" style="font-size: 8rem; color: #94a3b8;">category</span>
                    @endif
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">qr_code</span>
                        Category Code:
                    </span>
                    <span class="info-value">{{ $category->code }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">category</span>
                        Category Name:
                    </span>
                    <span class="info-value">{{ $category->name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">toggle_on</span>
                        Status:
                    </span>
                    <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                        <span class="material-icons-outlined">
                            {{ $category->status == 'active' ? 'check_circle' : 'cancel' }}
                        </span>
                        {{ ucfirst($category->status) }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">calendar_today</span>
                        Created:
                    </span>
                    <span class="info-value">{{ $category->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">update</span>
                        Last Updated:
                    </span>
                    <span class="info-value">{{ $category->updated_at->format('M d, Y h:i A') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">person</span>
                        Created By:
                    </span>
                    <span class="info-value">{{ ucfirst($category->add_by) }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">update</span>
                        Updated By:
                    </span>
                    <span class="info-value">{{ ucfirst($category->update_by) }}</span>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="btn-group">
                        <a href="{{ route('seller.category.edit', $category->id) }}"
                           class="btn btn-primary">
                            <span class="material-icons-outlined">edit</span>
                            Edit Category
                        </a>
                        <a href="{{ route('seller.category.index') }}"
                           class="btn btn-secondary">
                            <span class="material-icons-outlined">arrow_back</span>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Products in this Category -->
        <div class="col-lg-7">
            <div class="category-details-card">
                <h5 class="d-flex align-items-center mb-4">
                    <span class="material-icons-outlined me-2">inventory</span>
                    Products in this Category
                    <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
                </h5>

                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image">
                                        @else
                                            <div class="product-image d-flex align-items-center justify-content-center">
                                                <span class="material-icons-outlined text-muted">image</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->code }}</small>
                                    </td>
                                    <td>
                                        <strong>Rs. {{ number_format($product->price, 2) }}</strong>
                                        @if($product->discount_price)
                                            <br>
                                            <small class="text-success">
                                                Discount: Rs. {{ number_format($product->discount_price, 2) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $product->stock }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <span class="material-icons-outlined">inventory_2</span>
                        <h5 class="text-muted">No Products Found</h5>
                        <p class="text-muted">No products are currently assigned to this category.</p>
                        <a href="{{ route('seller.product.create') }}" class="btn btn-primary mt-2">
                            <span class="material-icons-outlined me-1">add</span>
                            Add New Product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
