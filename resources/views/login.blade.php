<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Login | DX Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('asset/img/dx.png') }}">

    <style>
        :root {
            --main-theme-color: #10b981;
            --dark-green: #065f46;
            --light-green: #34d399;
            --accent-color: #059669;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-image: url("{{ asset('asset/img/shop.jpg') }}");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Mobile-first approach */
        .login-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-large);
            min-height: auto;
            flex-direction: column;
            margin: 0 auto;
        }

        /* Tablet and larger */
        @media (min-width: 768px) {
            .login-container {
                min-height: 600px;
            }
        }

        /* Desktop */
        @media (min-width: 992px) {
            .login-container {
                flex-direction: row;
                min-height: 650px;
            }
        }

        /* Left Side - Branding with Green Theme (Now first on mobile) */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(6, 95, 70, 0.9) 0%, rgba(5, 150, 105, 0.85) 100%);
            color: white;
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            order: 1;
        }

        @media (min-width: 992px) {
            .login-left {
                padding: 50px;
                order: 1;
            }
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .logo-container {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        @media (min-width: 992px) {
            .logo-container {
                justify-content: left;
                margin-bottom: 0;
            }
        }

        .logo-wrapper {
            background-color: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: var(--shadow-medium);
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        @media (min-width: 768px) {
            .logo-wrapper {
                padding: 20px;
                border-radius: 15px;
            }
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 0;
        }

        @media (min-width: 768px) {
            .logo {
                width: 150px;
            }
        }

        @media (min-width: 992px) {
            .logo {
                width: 180px;
            }
        }

        .left-content {
            position: relative;
            z-index: 1;
            margin-top: 20px;
        }

        @media (min-width: 992px) {
            .left-content {
                margin-top: 40px;
            }
        }

        .left-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            text-align: center;
        }

        @media (min-width: 768px) {
            .left-title {
                font-size: 2rem;
                text-align: left;
            }
        }

        @media (min-width: 992px) {
            .left-title {
                font-size: 2.5rem;
            }
        }

        .left-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 20px;
            max-width: 100%;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            text-align: center;
        }

        @media (min-width: 768px) {
            .left-subtitle {
                font-size: 1.1rem;
                text-align: left;
                max-width: 400px;
                margin-bottom: 30px;
            }
        }

        .features-list {
            list-style: none;
            margin-top: 20px;
            padding: 0;
        }

        @media (min-width: 992px) {
            .features-list {
                margin-top: 30px;
            }
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.9rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        @media (min-width: 768px) {
            .features-list li {
                font-size: 1rem;
                margin-bottom: 15px;
            }
        }

        .features-list i {
            margin-right: 10px;
            color: var(--light-green);
            font-size: 1rem;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));
            min-width: 20px;
        }

        @media (min-width: 768px) {
            .features-list i {
                font-size: 1.2rem;
                margin-right: 12px;
            }
        }

        .left-footer {
            position: relative;
            z-index: 1;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            font-size: 0.8rem;
            text-align: center;
            margin-top: 20px;
        }

        @media (min-width: 768px) {
            .left-footer {
                font-size: 0.9rem;
                text-align: left;
                margin-top: 0;
            }
        }

        /* Right Side - Login Form with Mirrored Glass Effect (Now on right side) */
        .login-right {
            flex: 1;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px) saturate(180%);
            -webkit-backdrop-filter: blur(15px) saturate(180%);
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            order: 2;
        }

        @media (min-width: 992px) {
            .login-right {
                padding: 60px 50px;
                border-left: 1px solid rgba(255, 255, 255, 0.3);
                order: 2;
            }
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(16, 185, 129, 0.1) 0%,
                rgba(5, 150, 105, 0.05) 100%
            );
            z-index: -1;
        }

        .login-right::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: rotate(45deg);
            animation: shimmer 8s infinite linear;
            z-index: -1;
        }

        @keyframes shimmer {
            0% {
                transform: rotate(45deg) translateX(-100%);
            }
            100% {
                transform: rotate(45deg) translateX(100%);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 10px;
        }

        @media (min-width: 768px) {
            .login-title {
                font-size: 2rem;
            }
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .login-form {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        @media (min-width: 576px) {
            .login-form {
                max-width: 400px;
            }
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        @media (min-width: 768px) {
            .form-group {
                margin-bottom: 25px;
            }
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        @media (min-width: 768px) {
            .form-label {
                font-size: 0.95rem;
            }
        }

        .input-group {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        @media (min-width: 768px) {
            .form-control {
                padding: 15px 15px 15px 50px;
                border-radius: 12px;
                font-size: 1rem;
            }
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            outline: none;
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
            transition: var(--transition);
        }

        @media (min-width: 768px) {
            .input-icon {
                left: 18px;
                font-size: 1.1rem;
            }
        }

        .form-control:focus + .input-icon {
            color: var(--accent-color);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .password-toggle:hover {
            color: var(--accent-color);
        }

        .form-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        @media (min-width: 576px) {
            .form-options {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                font-size: 0.9rem;
                margin-bottom: 25px;
            }
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--accent-color);
            width: 16px;
            height: 16px;
        }

        .forgot-password {
            color: var(--accent-color);
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
        }

        .forgot-password:hover {
            color: var(--dark-green);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--main-theme-color) 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
            cursor: pointer;
            margin-bottom: 20px;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .btn-login {
                padding: 16px;
                border-radius: 12px;
                font-size: 1.1rem;
                margin-bottom: 25px;
            }
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255,255,255,0.1), rgba(255,255,255,0.1));
            opacity: 0;
            transition: var(--transition);
        }

        .btn-login:hover::after {
            opacity: 1;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: var(--text-light);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider-text {
            padding: 0 15px;
            font-size: 0.85rem;
        }

        @media (min-width: 768px) {
            .divider {
                margin: 30px 0;
            }
            .divider-text {
                font-size: 0.9rem;
            }
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #e5e7eb;
            color: var(--text-light);
            transition: var(--transition);
            font-size: 1.1rem;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        @media (min-width: 768px) {
            .social-btn {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                font-size: 1.2rem;
            }
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .register-link {
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 20px;
        }

        @media (min-width: 768px) {
            .register-link {
                font-size: 0.95rem;
                margin-bottom: 0;
            }
        }

        .register-link a {
            color: var(--accent-color);
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
        }

        .register-link a:hover {
            color: var(--dark-green);
        }

        .security-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            padding: 12px;
            background: rgba(5, 150, 105, 0.05);
            border-radius: 8px;
            font-size: 0.8rem;
            color: var(--text-light);
            text-align: center;
        }

        @media (min-width: 768px) {
            .security-notice {
                margin-top: 30px;
                padding: 15px;
                border-radius: 10px;
                font-size: 0.85rem;
            }
        }

        .security-notice i {
            margin-right: 10px;
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        /* Animation classes */
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Extra small devices (phones) */
        @media (max-width: 375px) {
            body {
                padding: 10px;
            }

            .login-left, .login-right {
                padding: 20px 15px;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .left-title {
                font-size: 1.4rem;
            }

            .logo {
                width: 100px;
            }

            .btn-login {
                padding: 12px;
                font-size: 0.95rem;
            }
        }

        /* Landscape orientation fixes */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .login-container {
                min-height: auto;
            }

            .login-left {
                padding: 20px;
            }

            .login-right {
                padding: 20px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .btn-login {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Left Section - Branding with Green Theme -->
    <div class="login-left">
        <div class="logo-container">
            <div class="logo-wrapper animate__animated animate__fadeInRight">
                <img src="{{ asset('asset/img/dx.png') }}" alt="DX Platform Logo" class="logo">
            </div>
        </div>

        <div class="left-content">
            <h1 class="left-title animate__animated animate__fadeInRight">Secure Access to Your Business Dashboard</h1>
            <p class="left-subtitle animate__animated animate__fadeInRight animate__delay-1s">Manage your account, track performance, and grow your business with our comprehensive platform.</p>

            <ul class="features-list animate__animated animate__fadeInUp animate__delay-2s">
                <li><i class="fas fa-shield-alt"></i> Enterprise-grade security</li>
                <li><i class="fas fa-chart-line"></i> Real-time analytics</li>
                <li><i class="fas fa-users"></i> Team collaboration tools</li>
                <li><i class="fas fa-sync"></i> Automated workflows</li>
            </ul>
        </div>

        <div class="left-footer animate__animated animate__fadeInUp animate__delay-3s">
            <p>© 2023 DX Platform. All rights reserved.</p>
        </div>
    </div>

    <!-- Right Section - Login Form with Mirrored Glass Effect -->
    <div class="login-right">
        <div class="login-header">
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to continue to your account</p>
        </div>

        <form action="{{route('login.check')}}" method="POST" class="login-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="username">Username or Email</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="username" name="username" required autocomplete="false" placeholder="Enter your username or email">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <span class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">
                <span>Sign In</span>
            </button>

            <div class="register-link">
                Don't have an account? <a href="#" class="text-warning">Request Seller Account</a>
            </div>
        </form>

        <div class="security-notice">
            <i class="fas fa-lock"></i>
            <span>Your login session is secured with 256-bit SSL encryption</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        }

        // Add focus effects to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Handle form submission
        const form = document.querySelector('.login-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const username = document.getElementById('username');
                const password = document.getElementById('password');

                if (username && username.value.trim() === '') {
                    e.preventDefault();
                    alert('Please enter your username or email');
                    username.focus();
                    return false;
                }

                if (password && password.value.trim() === '') {
                    e.preventDefault();
                    alert('Please enter your password');
                    password.focus();
                    return false;
                }

                // Show loading state
                const submitBtn = this.querySelector('.btn-login');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
                    submitBtn.disabled = true;
                }
            });
        }
    });
</script>
</body>
</html>
