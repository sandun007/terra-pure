@extends('admin.app')

@push('title')
    Seller List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title d-flex align-items-center gap-3">
            <h4 class="fw-bold mb-0">Seller List</h4>
            <span class="badge bg-primary rounded-pill">{{ $sellers->count() }}</span>
        </div>

        <div class="page-btn mt-0">
            <a href="{{ route('seller.create') }}" class="btn btn-secondary">
                Create New Seller
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="product-table" class="display table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Seller Code</th>
                    <th>Seller Name</th>
                    <th>NIC</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>District</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sellers as $seller)
                    <tr>
                        <td>{{ $seller->code }}</td>
                        <td>{{ $seller->name }}</td>
                        <td>{{ $seller->NIC_no }}</td>
                        <td>{{ $seller->phone }}</td>
                        <td>{{ $seller->address }}</td>
                        <td>{{ $seller->district }}</td>
                        <td class="text-center">

                            <a href="{{ route('admin.seller.view', $seller->id) }}"
                               class="btn btn-dark btn-sm">
                                <i class="ti ti-eye"></i>
                            </a>

                            <a href="{{ route('admin.seller.edit', $seller->id) }}"
                               class="btn btn-warning btn-sm">
                                <i class="ti ti-edit"></i>
                            </a>

                            <button type="button"
                                    class="btn btn-danger btn-sm delete-confirm"
                                    data-url="{{ route('seller.delete', $seller->id) }}">
                                <i class="ti ti-trash"></i>
                            </button>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            // DataTable
            $('#product-table').DataTable();

            //  SUCCESS MESSAGE (Update / Create)
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
            @endif

            //  ERROR MESSAGE
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
            });
            @endif

            // 🗑 Delete Confirmation
            $(document).on('click', '.delete-confirm', function (e) {
                e.preventDefault();
                let deleteUrl = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });

        });
    </script>
@endpush
