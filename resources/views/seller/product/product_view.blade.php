<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product View</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        p {
            text-align: justify !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-success">
    <a class="navbar-brand text-white" href="#" onclick="window.history.back(); return false;">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</nav>

<div class="container-fluid mt-5">
    <div class="row">
        <!-- Left Column -->
        <div class="col-6" style="border-bottom: 2px solid green; border-bottom-right-radius: 30px;">
            <div class="row mb-5">
                <div class="col-12 d-flex justify-content-start">
                    <img src="{{asset('asset/img/wordmark.png')}}" alt="product image" width="150" class="mt-5">
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <img src="{{asset('asset/img/theme/item.png')}}" alt="product image" width="300" style="margin-top: 10px">
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-6">
            <div class="row ml-2 mt-4">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <h2><i class="fas fa-box-open text-info"></i> Product Name</h2>
                        </div>
                        <div class="col-6">
                            <p class="mt-3">
                                <i class="fas fa-barcode text-primary"></i> Product Code :
                                <span class="bg-primary text-white p-2 rounded">ITM/00000000000001</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <h5><i class="fas fa-align-left text-secondary"></i> Description</h5>
                </div>
                <div class="col-12">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi aperiam harum modi quasi quisquam
                        saepe sit vitae. Est, quibusdam quidem. Animi consectetur cupiditate debitis in ipsa placeat quod.
                        Accusamus amet asperiores at corporis culpa cumque dolorum eaque exercitationem facere natus numquam
                        odio qui quibusdam, rerum soluta sunt totam voluptates voluptatibus!</p>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <h2 style="color: orangered">
                                <i class="fas fa-tag"></i> Rs.1500.00
                            </h2>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control" placeholder="Qty">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <h2 class="mt-3">
                        <i class="fas fa-money-bill-wave text-success"></i> Total Amount : Rs.3000.00
                    </h2>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-cart-plus"></i> Add To Cart
                    </button>
                </div>
                <div class="col-12">
                    <button class="btn btn-success w-100 mt-3">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>
