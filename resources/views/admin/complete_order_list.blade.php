@extends('admin.app')

@push('title')
    Complete Order List
@endpush

@push('css')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>

        .phone-img img{
            width:100px;
            height:100px;
            object-fit:cover;
            border:1px solid #ccc;
            border-radius:4px;
        }

        .phone-img a{
            position:absolute;
            top:2px;
            right:2px;
            background:rgba(255,255,255,0.7);
            border-radius:50%;
            padding:2px;
        }



        .dataTables_wrapper .dataTables_filter{
            float:right;
        }

        .dataTables_wrapper .dataTables_length{
            float:left;
        }

        .dataTables_wrapper .dataTables_info{
            padding-top:15px;
        }

        .dataTables_wrapper .dataTables_paginate{
            float:right;
        }

        table.dataTable thead th{
            font-weight:600;
        }

    </style>

@endpush


@section('content')

    <div class="page-header">

        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Complete Orders</h4>
            </div>
        </div>

        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>

            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" title="Collapse">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>

    </div>


    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <table id="complete-order-table" class="table table-striped table-bordered" style="width:100%">

                        <thead>

                        <tr>
                            <th>Order Number</th>
                            <th>Product Name</th>
                            <th>Total Amount</th>
                            <th>Completed Date</th>
                        </tr>

                        </thead>

                        <tbody>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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

    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $(document).ready(function () {

            $('#complete-order-table').DataTable({

                responsive:true,

                pageLength:10,

                lengthMenu:[10,25,50,100],

                language:{
                    search:"",
                    searchPlaceholder:"Search Completed Orders..."
                }

            });

        });

    </script>

@endpush
