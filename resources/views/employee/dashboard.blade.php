@extends('employee.app')
@push('title')
    Pending Packing
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
                <h4 class="fw-bold">Pending Packing</h4>
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
        {{--        <div class="page-btn mt-0">--}}
        {{--            <a href="{{route('seller.create')}}" class="btn btn-secondary">Create New Seller</a>--}}
        {{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order Code</th>
                            <th>Seller Name</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Mobile</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            @php
                                $seller = \App\Models\Saler::where('code', $order->seller_no)->first();
                            @endphp
                            <tr>
                                <td>{{ $order->order_no }}</td>
                                <td>{{ $seller->name ?? 'N/A' }}</td>
                                <td>{{ $order->item_code }}</td>
                                <td>{{ $order->item_name }}</td>
                                <td>{{ $order->customer_phone_01 }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    <a href="{{route('employee.order_view',$order->id)}}" class="btn btn-dark btn-sm">View</a>
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
            $('#product-table').DataTable();
        });
    </script>

@endpush
