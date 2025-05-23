@extends('admin.app')
@push('title')
    Return Order
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
    </style>

@endpush
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Return Order</h4>
                <h6>14500</h6>
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
            <a href="{{route('complete-order')}}" class="btn btn-secondary">Complete Order</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('pending-order')}}" class="btn btn-secondary">Pending Order</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('rescheduling-order')}}" class="btn btn-secondary">Rescheduling Order</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Delivery Date</th>
                            <th>Return Date</th>
                            <th>Seller</th>
                            <th>Customer</th>
                            <th>District</th>
                            <th>Remark</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>ORD/00001</td>
                            <td>INV/00001</td>
                            <td>2025.05.22</td>
                            <td>2025.05.30</td>
                            <td>2025.05.30</td>
                            <td>Suranga</td>
                            <td>Mallika</td>
                            <td>Hambantota</td>
                            <td>Damage</td>
                            <td>Rs.2560.00</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                                <button class="btn btn-warning btn-sm">Update</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ORD/00001</td>
                            <td>INV/00001</td>
                            <td>2025.05.22</td>
                            <td>2025.05.30</td>
                            <td>2025.05.30</td>
                            <td>Suranga</td>
                            <td>Mallika</td>
                            <td>Hambantota</td>
                            <td>Damage</td>
                            <td>Rs.2560.00</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                                <button class="btn btn-warning btn-sm">Update</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ORD/00001</td>
                            <td>INV/00001</td>
                            <td>2025.05.22</td>
                            <td>2025.05.30</td>
                            <td>2025.05.30</td>
                            <td>Suranga</td>
                            <td>Mallika</td>
                            <td>Hambantota</td>
                            <td>Damage</td>
                            <td>Rs.2560.00</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                                <button class="btn btn-warning btn-sm">Update</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ORD/00001</td>
                            <td>INV/00001</td>
                            <td>2025.05.22</td>
                            <td>2025.05.30</td>
                            <td>2025.05.30</td>
                            <td>Suranga</td>
                            <td>Mallika</td>
                            <td>Hambantota</td>
                            <td>Damage</td>
                            <td>Rs.2560.00</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                                <button class="btn btn-warning btn-sm">Update</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
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
            $('#product-table').DataTable();
        });
    </script>

@endpush
