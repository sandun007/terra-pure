@extends('admin.app')
@push('title')
    Product List
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .phone-img {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .phone-img img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .phone-img a {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            padding: 2px;
        }

        .bg-green {
            background-color: var(--main-theme-color) !important;
        }

        .status-active {
            color: #28a745;
            font-weight: bold;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Product List</h4>
                <h6>{{ $count }} Products</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                        class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                    data-bs-original-title="Collapse"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{ route('product.create') }}" class="btn btn-secondary">Create New Product</a>
        </div>
    </div>

    {{--    @if (session('success')) --}}
    {{--        <div class="alert alert-success alert-dismissible fade show" role="alert"> --}}
    {{--            {{ session('success') }} --}}
    {{--            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    {{--        </div> --}}
    {{--    @endif --}}

    {{--    @if (session('error')) --}}
    {{--        <div class="alert alert-danger alert-dismissible fade show" role="alert"> --}}
    {{--            {{ session('error') }} --}}
    {{--            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    {{--        </div> --}}
    {{--    @endif --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Filter Dropdowns -->
                    <div class="mb-3 d-flex flex-wrap align-items-end gap-3">
                        <div>
                            <label for="productTypeFilter" class="form-label mb-1 fw-semibold">Filter by Type</label>
                            <select id="productTypeFilter" class="form-select" style="min-width:160px;">
                                <option value="">All Types</option>
                                <option value="Admin">Admin</option>
                                <option value="Seller">Seller</option>
                                <option value="Wholesaler">Wholesaler</option>
                            </select>
                        </div>
                        <div id="creatorContainer" style="display:none;">
                            <label for="productCreatorFilter" class="form-label mb-1 fw-semibold">Select <span id="creatorTypeLabel">Creator</span></label>
                            <select id="productCreatorFilter" class="form-select" style="min-width:180px;">
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="ms-auto">
                            <label for="productSearch" class="form-label mb-1 fw-semibold">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" id="productSearch" class="form-control" placeholder="Search products..." style="min-width:200px;">
                            </div>
                        </div>
                    </div>
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Store</th>
                                <th>Created By</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr data-type="{{ $product->store }}" data-creator="{{ $product->creator_name }}">
                                    <td>{{ $product->code }}</td>
                                    <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </td>
                                    <td>{{ $product->store }}</td>
                                    <td>{{ $product->creator_name }}</td>
                                    <td>{{ $product->category->name ?? $product->category }}</td>
                                    <td>{{ $product->brand->name ?? $product->brand }}</td>
                                    <td>
                                        Rs.
                                        {{ number_format($product->display_price ?? ($product->selling_price ?? 0), 2) }}
                                    </td>
                                    <td>{{ $product->qty }}</td>
                                    <td>
                                        <span
                                            class="{{ $product->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('product.view', ['type' => strtolower($product->store), 'id' => $product->id]) }}"
                                                class="btn btn-dark btn-sm" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('product.edit', ['type' => strtolower($product->store), 'id' => $product->id]) }}"
                                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('product.delete', ['type' => strtolower($product->store), 'id' => $product->id]) }}"
                                                class="btn btn-danger btn-sm delete-btn" data-bs-toggle="tooltip"
                                                data-product-name="{{ $product->name }}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var table = $('#product-table').DataTable({
                info: false,
                searching: true,
                dom: 'lrtip',
                responsive: true,
                columnDefs: [
                    {
                        targets: [2],
                        visible: false,
                        searchable: true
                    },
                    {
                        targets: [2, 3],
                        render: function(data, type, row) {
                            if (type === 'filter' || type === 'sort') {
                                return $('<div>').html(data).text().trim();
                            }
                            return data;
                        }
                    }
                ]
            });

            // Helper: escape regex special chars
            function escapeRegex(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            // Wire custom search input to DataTable global search
            $('#productSearch').on('keyup', function() {
                table.search($(this).val()).draw();
            });

            // Cascading Filter Logic
            $('#productTypeFilter').on('change', function() {
                var type = $(this).val();

                // Reset Creator Filter
                $('#productCreatorFilter').html('<option value="">All</option>').val('');

                if (type === 'Seller' || type === 'Wholesaler') {
                    $('#creatorTypeLabel').text(type);
                    $('#creatorContainer').show();

                    // Fetch creators via AJAX
                    $.ajax({
                        url: "{{ route('product.get-creators') }}",
                        method: "GET",
                        data: { type: type },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    var fullName = item.name + ' (' + type + ')';
                                    $('#productCreatorFilter').append(
                                        '<option value="' + fullName + '">' + item.name + '</option>'
                                    );
                                });
                            }
                        }
                    });

                    // Filter table to show only this type (Column index 2 is Store)
                    table.column(2).search('^' + escapeRegex(type) + '$', true, false).draw();
                } else if (type === 'Admin') {
                    $('#creatorContainer').hide();
                    table.column(2).search('^Admin$', true, false).draw();
                } else {
                    $('#creatorContainer').hide();
                    table.column(2).search('').draw();
                }
                
                // Clear the created by filter whenever type changes
                table.column(3).search('').draw();
            });

            $('#productCreatorFilter').on('change', function() {
                var creator = $(this).val();
                if (creator) {
                    // Column index 3 is Created By
                    table.column(3).search('^' + escapeRegex(creator) + '$', true, false).draw();
                } else {
                    // If no creator selected, revert to just type filter
                    table.column(3).search('').draw();
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            //  Success Alert (Create / Update)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif

            //  Error Alert
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    timer: 4000,
                    timerProgressBar: true,
                });
            @endif

            //  Delete Confirmation
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                const deleteUrl = $(this).attr('href');
                const productName = $(this).data('product-name');

                Swal.fire({
                    title: 'Delete Product?',
                    html: `Are you sure you want to delete <strong>${productName}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete it!',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Redirect to delete route
                        window.location.href = deleteUrl;
                    }
                });
            });

        });
    </script>
@endpush
