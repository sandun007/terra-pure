@extends('seller.layout.app')
@push('title')
    {{ $brand->name }} - Brand Details
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
            --transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .page-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius-2xl);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
        }

        .page-header-content {
            position: relative;
            z-index: 1;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .breadcrumb-nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .brand-logo-large {
            width: 100px;
            height: 100px;
            border-radius: var(--radius-xl);
            background: white;
            padding: 0.5rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .brand-logo-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--radius-lg);
        }

        .brand-logo-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 800;
            font-size: 2rem;
            border-radius: var(--radius-lg);
        }

        .brand-title-section h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .brand-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .brand-code-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: var(--gradient-success);
            color: white;
        }

        .status-badge.inactive {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .header-actions {
            margin-left: auto;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition);
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-header-primary {
            background: white;
            color: var(--primary);
        }

        .btn-header-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: var(--primary-dark);
        }

        .btn-header-outline {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .btn-header-outline:hover {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .btn-header-danger {
            background: rgba(239, 68, 68, 0.2);
            color: white;
            border: 2px solid rgba(239, 68, 68, 0.5);
        }

        .btn-header-danger:hover {
            background: var(--danger);
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
            transition: all var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--info); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 1rem;
            color: var(--gray-900);
            font-weight: 500;
        }

        /* Products Table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--gray-200);
            background: var(--gray-50);
        }

        .products-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .products-table tbody tr {
            transition: all var(--transition);
        }

        .products-table tbody tr:hover {
            background: var(--gray-50);
        }

        .product-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-lg);
            object-fit: cover;
            background: var(--gray-100);
        }

        .product-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .product-category {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Empty State */
        .empty-products {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-products .material-icons-outlined {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .brand-header {
                flex-direction: column;
                text-align: center;
            }

            .brand-title-section {
                text-align: center;
            }

            .brand-meta {
                justify-content: center;
            }

            .header-actions {
                margin-left: 0;
                width: 100%;
                justify-content: center;
            }

            .btn-header {
                flex: 1;
                justify-content: center;
            }

            .products-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .brand-logo-large {
                width: 80px;
                height: 80px;
            }

            .brand-title-section h1 {
                font-size: 1.5rem;
            }

            .header-actions {
                flex-direction: column;
            }

            .btn-header {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <nav class="breadcrumb-nav">
                <a href="{{ route('seller.brands.index') }}">
                    <span class="material-icons-outlined" style="font-size: 1rem;">arrow_back</span>
                    Back to Brands
                </a>
                <span>/</span>
                <span>{{ $brand->name }}</span>
            </nav>

            <div class="brand-header">
                <div class="brand-logo-large">
                    @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}"
                             alt="{{ $brand->name }}"
                             onerror="this.parentElement.innerHTML='<div class=\'brand-logo-placeholder\'>{{ strtoupper(substr($brand->name, 0, 2)) }}</div>'">
                    @else
                        <div class="brand-logo-placeholder">
                            {{ strtoupper(substr($brand->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div class="brand-title-section">
                    <h1>{{ $brand->name }}</h1>
                    <div class="brand-meta">
                        <span class="brand-code-badge">
                            <span class="material-icons-outlined" style="font-size: 0.875rem; vertical-align: middle;">tag</span>
                            {{ $brand->code }}
                        </span>
                        <span class="status-badge {{ $brand->status }}">
                            <span class="material-icons-outlined" style="font-size: 0.875rem;">
                                {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                            </span>
                            {{ ucfirst($brand->status) }}
                        </span>
                    </div>
                </div>

                <div class="header-actions">
                    <a href="{{ route('seller.brands.edit', $brand->id) }}" class="btn-header btn-header-primary">
                        <span class="material-icons-outlined">edit</span>
                        Edit Brand
                    </a>
                    <a href="{{ route('seller.brands.index') }}" class="btn-header btn-header-outline">
                        <span class="material-icons-outlined">list</span>
                        All Brands
                    </a>
                    @if($brand->products_count == 0)
                        <button class="btn-header btn-header-danger" onclick="deleteBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}')">
                            <span class="material-icons-outlined">delete</span>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon primary">
                <span class="material-icons-outlined">inventory_2</span>
            </div>
            <div class="stat-value">{{ $brand->products_count ?? 0 }}</div>
            <div class="stat-label">Total Products</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <span class="material-icons-outlined">{{ $brand->status == 'active' ? 'visibility' : 'visibility_off' }}</span>
            </div>
            <div class="stat-value">{{ ucfirst($brand->status) }}</div>
            <div class="stat-label">Visibility Status</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <span class="material-icons-outlined">calendar_today</span>
            </div>
            <div class="stat-value">{{ $brand->created_at->format('M d') }}</div>
            <div class="stat-label">Created {{ $brand->created_at->format('Y') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <span class="material-icons-outlined">update</span>
            </div>
            <div class="stat-value">{{ $brand->updated_at->diffForHumans() }}</div>
            <div class="stat-label">Last Updated</div>
        </div>
    </div>

    <!-- Brand Details -->
    <div class="content-card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="material-icons-outlined">info</span>
                Brand Information
            </h5>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Brand Code</span>
                    <span class="info-value">{{ $brand->code }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Brand Name</span>
                    <span class="info-value">{{ $brand->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ ucfirst($brand->status) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created At</span>
                    <span class="info-value">{{ $brand->created_at->format('F d, Y \a\t h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value">{{ $brand->updated_at->format('F d, Y \a\t h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Products</span>
                    <span class="info-value">{{ $brand->products_count ?? 0 }} product(s)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="content-card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="material-icons-outlined">shopping_bag</span>
                Products Under This Brand
            </h5>
            @if(isset($allProducts) && $allProducts->count() > 0)
                <span style="font-size: 0.875rem; color: var(--gray-500);">
                    Showing {{ $allProducts->count() }} of {{ $allProducts->total() }}
                </span>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">
            @if(isset($recentProducts) && $recentProducts->count() > 0)
                <div class="table-responsive">
                    <table class="products-table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentProducts as $product)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-image">
                                        @else
                                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: var(--gray-200);">
                                                <span class="material-icons-outlined" style="color: var(--gray-400);">image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="product-name">{{ $product->name }}</div>
                                            <div class="product-category">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                        <span style="font-size: 0.875rem;">
                                            {{ $product->category->name ?? 'N/A' }}
                                            @if($product->subcategory)
                                                <br><small style="color: var(--gray-400);">{{ $product->subcategory->name }}</small>
                                            @endif
                                        </span>
                                </td>
                                <td>
                                        <span class="status-badge {{ $product->status ?? 'inactive' }}" style="font-size: 0.7rem; padding: 0.375rem 0.75rem;">
                                            {{ ucfirst($product->status ?? 'N/A') }}
                                        </span>
                                </td>
                                <td>
                                        <span style="font-size: 0.875rem; color: var(--gray-500);">
                                            {{ $product->created_at->format('M d, Y') }}
                                        </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(isset($allProducts) && $allProducts->hasPages())
                    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100);">
                        {{ $allProducts->links() }}
                    </div>
                @endif
            @else
                <div class="empty-products">
                    <span class="material-icons-outlined">inventory_2</span>
                    <h4 style="margin-bottom: 0.5rem; color: var(--gray-700);">No Products Yet</h4>
                    <p>This brand doesn't have any products assigned to it.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteBrand(brandId, brandName) {
            Swal.fire({
                title: 'Delete Brand?',
                html: `
                    <div style="text-align: center; padding: 1rem 0;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center;">
                            <span class="material-icons-outlined" style="font-size: 40px; color: #ef4444;">delete_forever</span>
                        </div>
                        <h4 style="font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">${brandName}</h4>
                        <p style="color: #64748b; font-size: 0.9375rem;">This will permanently delete the brand and cannot be undone.</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;">delete</span> Delete Brand',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3',
                    confirmButton: 'rounded-2',
                    cancelButton: 'rounded-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `/seller/brands/${brandId}`;
                    form.submit();
                }
            });
        }
    </script>
@endpush
