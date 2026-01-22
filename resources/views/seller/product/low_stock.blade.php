@extends('seller.layout.app')
@push('title')
    Low Stock
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
                <h4 class="fw-bold">Low Stock</h4>
                <h6>50</h6>
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
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Product No</th>
                            <th>Product Name</th>
                            <th>Stores</th>
                            <th>Qty</th>
                            <th>Supplier Name</th>
                            <th>Supplier Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>ITM/00001</td>
                            <td>Hand Free</td>
                            <td>Colombo</td>
                            <td>10</td>
                            <td>Mallika</td>
                            <td>07776888888</td>
                        </tr>
                        <tr>
                            <td>ITM/00001</td>
                            <td>Hand Free</td>
                            <td>Colombo</td>
                            <td>10</td>
                            <td>Mallika</td>
                            <td>07776888888</td>
                        </tr>
                        <tr>
                            <td>ITM/00001</td>
                            <td>Hand Free</td>
                            <td>Colombo</td>
                            <td>10</td>
                            <td>Mallika</td>
                            <td>07776888888</td>
                        </tr>
                        <tr>
                            <td>ITM/00001</td>
                            <td>Hand Free</td>
                            <td>Colombo</td>
                            <td>10</td>
                            <td>Mallika</td>
                            <td>07776888888</td>
                        </tr>
                        <tr>
                            <td>ITM/00001</td>
                            <td>Hand Free</td>
                            <td>Colombo</td>
                            <td>10</td>
                            <td>Mallika</td>
                            <td>07776888888</td>
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
