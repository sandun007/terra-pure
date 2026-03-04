@extends('admin.app')
@push('title')
 Category List
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
        .bg-green{
            background-color: var(--main-theme-color) !important;
        }
    </style>

@endpush
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Category List</h4>
                <h6>Showing {{ $count }} {{ \Illuminate\Support\Str::plural('category', $count) }}</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"
                   data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                   data-bs-original-title="Collapse"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{ route('category.create') }}" class="btn btn-secondary">Create New Category</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Category Code</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->code }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="{{ $category->status_badge }}">
                                        {{ $category->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('category.show', $category->id) }}" class="btn btn-dark btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Update
                                    </a>
                                    <a href="{{ route('category.delete',$category->id) }}" class="btn btn-danger btn-sm confirm-delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
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
    <script>
        $(document).ready(function () {
            $('#product-table').DataTable({
                info: false // removes "Showing 1 to X of Y entries"
            });

            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            @endif

            $(document).on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

@endpush
