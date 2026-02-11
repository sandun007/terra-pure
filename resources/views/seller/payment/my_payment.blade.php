@extends('seller.layout.app')
@push('title')
    My Payment
@endpush
@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h3>My Payment</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash1.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="307144.00">307144</span></h5>
                    <h6>Total Sales</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash1 w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash2.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="4385.00">4385</span></h5>
                    <h6>Total Commission</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash2 w-100">
                <div class="dash-widgetimg">
                    <span><i class="fas fa-user-tag"></i></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5><span class="counters" data-count="385656.50">385656.5</span></h5>
                    <h6>Total Available Amount</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-widget dash3 w-100">
                <div class="dash-widgetimg">
                    <span><img src="{{asset('asset/img/theme/dash4.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>RS.<span class="counters" data-count="40000.00">40000</span></h5>
                    <h6>Total Withdraw</h6>
                </div>
            </div>
        </div>

    </div>
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
@endsection


