@extends('seller.layout.app')
@push('title')
    My Product
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
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">My Product</h4>
            <h6>14500</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Descriptions</th>
                            <th>Real Price</th>
                            <th>Selling Price</th>
                            <th>Margin</th>
                            <th>Stock Quantity</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
                            </td>
                        </tr>
                        <tr>
                            <td>ITM/001</td>
                            <td>Lux saban</td>
                            <td>lassana ruwak</td>
                            <td>100.00</td>
                            <td>150.00</td>
                            <td>50.00</td>
                            <td>1000</td>
                            <td>
                                <button class="btn btn-primary btn-sm">view</button>
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
