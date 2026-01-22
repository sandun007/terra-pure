@extends('seller.layout.app')

@push('title')
    Other Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Other Call List</h4>
            </div>
        </div>
        <ul class="table-top-head">
            <li><a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"><i
                            class="ti ti-refresh"></i></a></li>
            <li><a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"><i
                            class="ti ti-chevron-up"></i></a></li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{route('call.index')}}" class="btn btn-secondary">Pending Call</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('call.conform')}}" class="btn btn-secondary">Conform</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('call.reject')}}" class="btn btn-secondary">Reject Call</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('call.not_answer_call_list')}}" class="btn btn-secondary">Not Answer</a>
        </div>
        <div class="page-btn mt-0">
            <a href="{{route('call.other_call_list')}}" class="btn btn-secondary">Other</a>
        </div>
    </div>
    <!--<div class="row">-->
    <!--    <div class="col-2">-->
    <!--        <a href="{{route('orders.export')}}" class="btn btn-success">Excel Export</a>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th class='d-none'></th>
                            <th>Order No</th>
                            <th>Product Code</th>
                            <th>Order Date</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            @php
                                $status = strtolower($order->status);
                                $class = match($status) {
                                    'reject' => 'status-reject',
                                    'conform', 'confirm' => 'status-confirm',
                                    'pending' => 'status-pending',
                                    'not answer', 'not anser' => 'status-not-answer',
                                    'whatsapp' => 'status-whatsapp',
                                    default => '',
                                };
                            @endphp
                            <tr class="{{ $class }}">
                                <td class='d-none'>{{ $order->id }}</td>
                                <td>{{ $order->order_no }}</td>
                                <td>{{ $order->item_code }}</td>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>
                                    <a href="{{ route('call.view', $order->id) }}"
                                       class="btn btn-primary btn-sm">View</a>
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
    <script>
        $('#product-table').DataTable({
            order: [[0, 'desc']] // Change 0 to the correct column index for `id`
        });

    </script>
@endpush
