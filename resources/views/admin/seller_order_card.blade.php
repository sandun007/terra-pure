<div class="card shadow-sm h-100 border-0 mt-5 ">

    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">
            {{ $order->order_number }}
        </h6>
    </div>

    <div class="card-body">

        <ul class="list-group list-group-flush">

            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Product</span>
                <span>{{ $order->product_name }}</span>
            </li>

            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Customer</span>
                <span>{{ $order->customer_name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Qty</span>
                <span>{{ $order->quantity }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Total Amount</span>
                <span>{{ $order->total_amount }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Shipping Address</span>
                <span>{{ $order->customer_address }}</span>
            </li>



            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-semibold">Date</span>
                <span>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
            </li>





        </ul>

    </div>

    <div class="card-footer text-end bg-light">

    </div>








</div>
