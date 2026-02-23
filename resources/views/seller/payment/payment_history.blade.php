@extends('seller.layout.app')
@push('title')
    Payment History
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Payment History</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Transaction History</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table dashboard-expired-products">
                                <thead>
                                <tr>
                                    <th>Ref No</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Amount</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        TRS/0001
                                    </td>
                                    <td>Deposit For Register</td>
                                    <td>29 Mar 2023</td>
                                    <td>Deposit</td>
                                    <td>BOC Bank</td>
                                    <td>DreamX Pvt Ltd</td>
                                    <td>Rs.5000.00</td>
                                    <td class="action-table-data">
                                        <button class="btn btn-primary btn-sm">View</button>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
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
