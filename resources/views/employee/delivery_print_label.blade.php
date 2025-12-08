<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4 Labels per A4</title>

    <!--<link rel="stylesheet"-->
    <!--      href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"-->
    <!--      crossorigin="anonymous">-->

    <style>
        @media print {
            @page {
                size: A4;
                margin: 0m;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .label-container {
                display: flex;
                flex-wrap: wrap;
            }
            .label {
                width: 50%;     /* 2 labels per row */
                height: 25%;    /* 2 rows per page */
                /*box-sizing: border-box;*/
                /*padding: 5mm;*/
                /*border: 1px solid #000;*/
            }
            table {
                width: 100%;
                font-size: 13px;
                border-collapse: collapse;
            }
            th, td {
                vertical-align: top;
                padding: 2px;
            }
        }
    </style>
</head>
<body>

<div class="label-container">
    <!-- Repeat this block for each label -->
    <div class="label">
        <table border='1'>
            <thead>
            <tr>
                <th>Seller Details</th>
                <th>Customer Details</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <p><b>Name : Direct Supplier</b></p>
                    <p><b>Telephone : {{$seller->phone}}</b></p>
                </td>
                <td>
                    <p><b>Name : {{$order->customer_name}}</b></p>
                    <p><b>Address : {{$order->customer_address}}</b></p>
                    <p><b>Telephone : {{$order->customer_phone_01}} / {{$order->customer_phone_02}}</b></p>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="text-center">Order Details</th>
            </tr>
            <tr>
                <td>
                    <!--<p>Order Number : {{$order->order_no}}</p>-->
                    <!--<p>Order Date: {{ today()->format('Y-m-d') }}</p>-->
                    <!--<p>Postal Code : 0000</p>-->
                    <p><b>Weight : 01</b></p>
                </td>
                <td>
                    <!--<p>Description : N/A</p>-->
                    <p><b>City : {{$order->city}}</b></p>
                    <p><b>Total COD : {{$order->total}}</b></p>

                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.print();
</script>
</body>
</html>
