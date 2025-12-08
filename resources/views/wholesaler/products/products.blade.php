@extends('wholesaler.layouts.app')
@push('title')
    My Products
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        /* ... your existing styles ... */

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
        }

        .btn-edit:hover, .btn-delete:hover {
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-confirm {
            border-radius: 15px;
            border: none;
        }

        .modal-confirm .modal-header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-confirm .modal-body {
            padding: 30px;
            text-align: center;
        }

        .modal-confirm .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #fef2f2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-confirm .icon-box i {
            font-size: 36px;
            color: #ef4444;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="fw-bold">My Products</h4>
                <h6>Total Products: {{ $products->count() }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('wholesaler.products.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Wholesale Price</th>
                            <th>Stock</th>
                            <th>Min Order Qty</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="product-img">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-box text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small
                                                class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><strong>RS. {{ number_format($product->price, 2) }}</strong></td>
                                <td><strong>RS. {{ number_format($product->wholesale_price, 2) }}</strong></td>
                                <td>
                                    <span class="badge {{ $product->stock < 10 ? 'bg-danger' : ($product->stock < 50 ? 'bg-warning' : 'bg-success') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>{{ $product->min_order_quantity }}</td>
                                <td>
                                    <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('wholesaler.products.edit', $product->id) }}"
                                           class="btn btn-edit btn-action">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-delete btn-action" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $product->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content modal-confirm">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="icon-box">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                    <h4>Are you sure?</h4>
                                                    <p>Do you really want to delete "{{ $product->name }}"? This process
                                                        cannot be undone.</p>
                                                    <div class="d-flex justify-content-center gap-3">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel
                                                        </button>
                                                        <form action="{{ route('wholesaler.products.destroy', $product->id) }}"
                                                              method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No products found</h5>
                                    <p class="text-muted">Start by adding your first product</p>
                                    <a href="{{ route('wholesaler.products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Add Product
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#product-table').DataTable({
                "language": {
                    "emptyTable": "No products available",
                    "zeroRecords": "No matching products found"
                }
            });
        });
    </script>
@endpush
