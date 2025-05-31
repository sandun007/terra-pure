<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">

    <style>
        :root {
            --main-theme-color: #007bff; /* fallback if not defined in main.css */
        }

        body {
            background-image: url("{{ asset('asset/img/bg3.png') }}");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="text-center mb-3">
        <img src="{{ asset('asset/img/dx.png') }}" alt="Logo" width="200">
    </div>
    <form action="" method="POST">
        <div class="mb-2">
            <label>Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <a href="{{ route('admin.dashboard') }}" class="btn w-100 text-white" style="background-color: var(--main-theme-color)">Login</a>
        </div>
        <div class="row">
            <div class="col-6 text-start">
                <a href="#" class="text-warning">Request Seller Account</a>
            </div>
            <div class="col-6 text-end">
                <a href="#" class="text-danger">Forgot password</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>
