@extends('admin.app')

@push('title')
 New Order
@endpush
@push('css')
    <style>


        .pro-card {
            border-radius: 10px;
            padding: 10px;
            box-shadow: 5px 5px 10px  rgba(0,0,0,0.3);
            width: 350px;

        }
        .pro-row {
            padding: 10px;
        }



    </style>

@endpush
@section('content')
    <div class="page-header">
        <div class="d-flex ">
            <div>
                <h4>New Orders</h4>
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


        <!-- Tabs -->
        <nav class="nav nav-pills flex-column flex-sm-row" id="orderTabs">
            <a class="flex-sm-fill text-sm-center nav-link active m-1"
               data-bs-toggle="pill"
               href="#companyorder">
                Company Orders
            </a>

            <a class="flex-sm-fill text-sm-center nav-link  m-1"
               data-bs-toggle="pill"
               href="#wholeseller">
                Whole Seller Orders
            </a>
        </nav>

        <!-- TAB CONTENT START -->
        <div class="tab-content mt-">

            <!-- Whole Seller Orders -->
            <div class="tab-pane fade show active" id="companyorder" >
                <div class="row pro-row">


                    @foreach($sellerorders as $order)

                        <div class="col-md-6">
                            <div class="pro-card  mt-3 mb-3">
                                @include('admin.seller_order_card',['order' => $order])
                            </div>
                        </div>

                    @endforeach

                </div>
            </div>

            <!-- Seller Orders -->
            <div class="tab-pane fade"  id="wholeseller" >
                <div class="row pro-row">


                    @foreach($wholesellerorders as $order)

                        <div class="col-md-6">
                            <div class="pro-card mt-3 mb-3">
                                @include('admin.wholeseller_order_card',['order' => $order])
                            </div>
                        </div>

                    @endforeach





                </div>
            </div>

        </div>



    </div>


















@endsection
