<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - Student Information System</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        .login-bg {
            background: url('assets/img/background.jpg') center/cover no-repeat;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            min-height: 450px;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            margin-bottom: 25px;
        }
        .logo-container img {
            max-height: 26px;
            vertical-align: middle;
        }
        .system-title {
            font-size: 24px;
            font-weight: 700;
            color: #012970;
            font-family: "Nunito", sans-serif;
            margin-bottom: 0;
        }

        /* Error Message Styling */
        .invalid-feedback, 
        .text-danger,
        .mt-2 {  /* This targets the x-input-error messages */
            color: #dc3545 !important;
            font-size: 0.75rem !important;  
            margin-top: 0.25rem !important;
            display: block;
            list-style-type: none !important;  
            padding-left: 0 !important;  
        }
 
        .invalid-feedback ul,
        .text-danger ul,
        .mt-2 ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .invalid-feedback li,
        .text-danger li,
        .mt-2 li {
            list-style: none;
        }
    </style>
</head>

<body class="login-bg">
    <div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="col-lg-4 col-md-6">
            <div class="card" style="min-height: 400px;">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="logo-container">
                        <img src="assets/img/logo.png" alt="">
                        <h4 class="system-title">Student Information System</h4>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus 
                                   autocomplete="username"
                                   placeholder="Enter your email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required
                                       autocomplete="current-password"
                                       placeholder="Enter your password">
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePassword()">
                                    <i class="bi bi-eye" id="togglePassword"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="remember_me" 
                                       name="remember">
                                <label class="form-check-label small" for="remember_me">
                                    Remember me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="small text-primary text-decoration-none">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Login
                        </button>

                        <!-- Register Link -->
                        <div class="text-center">
                            <span class="small">Don't have an account? </span>
                            <a href="{{ route('register') }}" class="small text-primary text-decoration-none">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
