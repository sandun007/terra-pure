@extends('wholesaler.layouts.app')
@push('title')
    Category Management
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .category-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .category-image-container {
            height: 150px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.05);
        }

        .no-image {
            font-size: 3rem;
            color: #94a3b8;
        }

        .category-code {
            background: rgba(102, 126, 234, 0.1);
            color: var(--main-theme-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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

        .product-count-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        /* Table Styles */
        .table-category-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 5px;
            border: 2px solid #e2e8f0;
        }

        #categories-table_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px;
            width: 300px !important;
            transition: all 0.3s ease;
            padding-left: 45px;
        }

        #categories-table_filter input:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        #categories-table_filter {
            position: relative;
        }

        #categories-table_filter:before {
            content: "search";
            font-family: 'Material Icons Outlined';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 10;
        }

        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }

        table.dataTable tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        /* Loading overlay */
        .swal2-container .swal2-loader {
            border-color: var(--main-theme-color) transparent var(--main-theme-color) transparent;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold d-flex align-items-center">
                    <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">category</span>
                    Category Management
                </h3>
                <p class="mb-0 opacity-90 d-flex align-items-center">
                    <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">format_list_bulleted</span>
                    {{ $count }} Categories in Catalog
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('wholesaler.categories.create') }}" class="btn btn-light d-flex align-items-center">
                    <span class="material-icons-outlined me-2">add_circle</span>
                    Add New Category
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <span class="material-icons-outlined me-2">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <span class="material-icons-outlined me-2">error</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($categories->isEmpty())
        <div class="empty-state">
            <span class="material-icons-outlined">category</span>
            <h4 class="text-muted mb-3">No Categories Found</h4>
            <p class="text-muted mb-4">Start by creating your first category for your products</p>
            <a href="{{ route('wholesaler.categories.create') }}" class="btn btn-primary d-flex align-items-center mx-auto" style="width: fit-content;">
                <span class="material-icons-outlined me-2">add_circle</span>
                Create First Category
            </a>
        </div>
    @else
        <!-- Grid View -->
        <div class="row mb-4">
            @foreach($categories as $category)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="category-card">
                        <div class="category-image-container">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="category-image"
                                     onerror="this.src='{{ asset('images/default-category.png') }}'">
                            @else
                                <span class="material-icons-outlined no-image">category</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $category->name }}</h5>
                                <span class="category-code">{{ $category->code }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="product-count-badge">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">inventory</span>
                                    {{ \App\Models\WholesalerProduct::where('category_id', $category->id)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }} products
                                </span>
                                <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">
                                        {{ $category->status == 'active' ? 'check_circle' : 'cancel' }}
                                    </span>
                                    {{ ucfirst($category->status) }}
                                </span>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('wholesaler.categories.edit', $category->id) }}"
                                   class="btn-action btn-edit">
                                    <span class="material-icons-outlined">edit</span>
                                    Edit
                                </a>
                                <button type="button"
                                        class="btn-action btn-delete delete-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                    <span class="material-icons-outlined">delete</span>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div class="card">
            <div class="card-body">
                <table id="categories-table" class="display table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Category Name</th>
                        <th>Code</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="table-category-image"
                                         onerror="this.src='{{ asset('images/default-category.png') }}'">
                                @else
                                    <div class="table-category-image d-flex align-items-center justify-content-center">
                                        <span class="material-icons-outlined text-muted">image</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $category->code }}</code>
                            </td>
                            <td>
                                    <span class="product-count-badge">
                                        {{ \App\Models\WholesalerProduct::where('category_id', $category->id)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }}
                                    </span>
                            </td>
                            <td>
                                    <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                            </td>
                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('wholesaler.categories.edit', $category->id) }}"
                                       class="btn btn-sm btn-outline-warning d-flex align-items-center">
                                        <span class="material-icons-outlined" style="font-size: 1rem;">edit</span>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger d-flex align-items-center delete-category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                        <span class="material-icons-outlined" style="font-size: 1rem;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#categories-table').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search categories...",
                    lengthMenu: "Show _MENU_ categories",
                    info: "Showing _START_ to _END_ of _TOTAL_ categories",
                    infoEmpty: "No categories available",
                    zeroRecords: "No matching categories found",
                    paginate: {
                        first: '<span class="material-icons-outlined">first_page</span>',
                        previous: '<span class="material-icons-outlined">chevron_left</span>',
                        next: '<span class="material-icons-outlined">chevron_right</span>',
                        last: '<span class="material-icons-outlined">last_page</span>'
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "align-middle", targets: "_all" }
                ],
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            // Get CSRF token from meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // SweetAlert for delete confirmation
            $(document).on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');
                const deleteUrl = "{{ route('wholesaler.categories.destroy', ':id') }}".replace(':id', categoryId);

                Swal.fire({
                    title: 'Are you sure?',
                    html: `<strong>Category:</strong> ${categoryName}<br><br>
                           This action will permanently delete the category and cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    return response;
                                } else {
                                    throw new Error(response.message || 'Delete failed');
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Request failed';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.statusText) {
                                    errorMessage = xhr.statusText;
                                }
                                throw new Error(errorMessage);
                            }
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Category has been deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Image error handling
            $('.category-image, .table-category-image').on('error', function() {
                $(this).attr('src', '{{ asset("images/default-category.png") }}');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@endpush
