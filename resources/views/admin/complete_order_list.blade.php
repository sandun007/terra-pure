@extends('admin.app')

@push('title')
    Complete Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>

        .table-card{
            border-radius:10px;
            padding:15px;
            box-shadow:5px 5px 10px rgba(0,0,0,0.2);
            background:white;
        }

    </style>
@endpush


@section('content')

    <div class="page-header">
        <div class="d-flex">
            <div>
                <h4>Completed Orders</h4>
            </div>
        </div>

        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" title="Refresh">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" id="collapse-header" title="Collapse">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>
    </div>


    <div class="row">

        <!-- Tabs -->
        <nav class="nav nav-pills flex-column flex-sm-row">

            <a class="flex-sm-fill text-sm-center nav-link active m-1"
               data-bs-toggle="pill"
               href="#completedcompanyorders">
                Completed Company Orders
            </a>

            <a class="flex-sm-fill text-sm-center nav-link m-1"
               data-bs-toggle="pill"
               href="#completedwholesellerorders">
                Completed Wholeseller Orders
            </a>

        </nav>


        <div class="tab-content mt-3">

            <!-- Company Orders Table -->
            <div class="tab-pane fade show active" id="completedcompanyorders">

                <div class="table-card">

                    <table id="companyTable" class="table table-bordered table-striped">

                        <thead class="table-dark">

                        <tr>
                            <th>Order Number</th>
                            <th>Product Name</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>

                        </tr>

                        </thead>

                        <tbody>



                        <tr>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>





                        </tr>







                        </tbody>

                    </table>

                </div>

            </div>



            <!-- Wholeseller Orders Table -->
            <div class="tab-pane fade" id="completedwholesellerorders">

                <div class="table-card">

                    <table id="wholesellerTable" class="table table-bordered table-striped">

                        <thead class="table-dark">

                        <tr>
                            <th>Order Number</th>
                            <th>Product Name</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                        </tr>

                        </thead>

                        <tbody>



                        <tr>

                            <td></td>
                            <td></td>
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



    <script>

        $(document).ready(function () {

            $('#companyTable').DataTable({
                pageLength: 5
            });

            $('#wholesellerTable').DataTable({
                pageLength: 5
            });

        });

    </script>
@endpush
