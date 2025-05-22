<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('asset/css/main.css')}}">

    <style>
        body {
            background-image: url("{{ asset('asset/img/bg2.png') }}");
            background-repeat: no-repeat;
            background-size: cover; /* ✅ This ensures full width and height */
            background-position: center center; /* ✅ Centered horizontally and vertically */
            background-attachment: fixed; /* Optional: keeps the background fixed on scroll */
            min-height: 100vh; /* Ensure body takes full viewport height */
            margin: 0; /* Remove default margin */
        }


    </style>
</head>
<body>
<div class="container" style="margin-top: 200px;">
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <div style="width: 500px">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <img src="{{asset('asset/img/logo.jpg')}}" alt="" width="300">
                    </div>
                    <form action="">
                    <div class="col-12 mb-2">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username">
                    </div>
                    <div class="col-12">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="col-12 mt-2">
                        <a href="{{route('admin.dashboard')}}" class="btn w-100 text-white" style="background-color: var(--main-theme-color)">Login</a>
                    </div>
                        <div class="col-12">
                            <div class="row mt-2">
                                <div class="col-6">
                                    <p ><a href="" class="text-warning" style="text-decoration: none;">Request Seller Account</a></p>
                                </div>

                                <div class="col-6">
                                    <p ><a href="" class="text-danger" style="text-decoration: none;">Forgot password</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
