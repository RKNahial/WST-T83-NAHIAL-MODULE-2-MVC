<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reset Password - Student Information System</title>

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
            min-height: 300px;
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
            <div class="card">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="logo-container">
                        <img src="assets/img/logo.png" alt="">
                        <h4 class="system-title">Student Information System</h4>
                    </div>

                    <div class="mb-4"
                        <p class="text-muted text-center small mb-4">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus 
                                   placeholder="Enter your email">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            {{ __('Email Password Reset Link') }}
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="small text-primary text-decoration-none">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
