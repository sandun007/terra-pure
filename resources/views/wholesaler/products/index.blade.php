@extends('wholesaler.app')
@push('title')
    My Products - Wholesale
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .products-container {
            padding: 30px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 25px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .product-body {
            padding: 20px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        .product-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: #64748b;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #667eea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .stock-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-good {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .add-product-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .add-product-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .search-input {
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
        }

        .empty-state i {
            font-size: 64px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="products-container">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-boxes me-2"></i>My Products</h1>
                    <p class="mb-0">Manage your wholesale product catalog</p>
                </div>
                <a href="{{ route('wholesaler.products.create') }}" class="add-product-btn">
                    <i class="fas fa-plus-circle"></i>
                    Add New Product
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="filter-section">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="search-input" placeholder="🔍 Search products...">
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">Total Products: <strong>{{ $products->count() }}</strong></span>
                </div>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3 class="mt-3">No Products Yet</h3>
                <p class="text-muted">Start adding products to your wholesale catalog</p>
                <a href="{{ route('wholesaler.products.create') }}" class="add-product-btn mt-3">
                    <i class="fas fa-plus-circle"></i>
                    Add Your First Product
                </a>
            </div>
        @else
            <div class="row" id="productsGrid">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 col-xl-3 product-item" data-name="{{ strtolower($product->name) }}">
                        <div class="product-card">
                            <div style="position: relative;">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif

                                @if($product->stock == 0)
                                    <span class="stock-badge stock-out">Out of Stock</span>
                                @elseif($product->stock < 10)
                                    <span class="stock-badge stock-low">Low Stock</span>
                                @else
                                    <span class="stock-badge stock-good">In Stock</span>
                                @endif
                            </div>

                            <div class="product-body">
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <div class="product-price">RS. {{ number_format($product->wholesale_price, 2) }}</div>

                                <div class="product-info">
                                    <span><i class="fas fa-box"></i> Stock: {{ $product->stock }}</span>
                                    <span><i class="fas fa-shopping-cart"></i> MOQ: {{ $product->min_order_quantity }}</span>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('wholesaler.products.edit', $product->id) }}" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('wholesaler.products.destroy', $product->id) }}" method="POST" class="d-inline" style="flex: 1;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete w-100" onclick="return confirm('Delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
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
@endsection

@push('script')
    <script>
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                const productName = product.getAttribute('data-name');
                if (productName.includes(searchTerm)) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    </script>
@endpush
