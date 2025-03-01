<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Student Information System</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">University Name</span>
            </a>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-primary mx-2">Login</a>
                        </li>
                    @endauth
                @endif
            </ul>
        </nav>
    </header>

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center" style="min-height: 100vh; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/img/university-bg.jpg') center/cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1">
                    <h1 class="text-white">Student Information System</h1>
                    <h2 class="text-white-50 mb-4">Your Gateway to Academic Excellence</h2>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Access Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Student Login</a>
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg">Faculty Login</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img">
                    <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- ======= Features Section ======= -->
    <section id="features" class="features section-bg py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>System Features</h2>
                <p>Everything you need to manage your academic journey</p>
            </div>

            <div class="row g-4">
                <!-- Academic Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-mortarboard fs-1 text-primary mb-3"></i>
                            <h4 class="card-title">Academic Records</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>View Grades & GWA</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Track Academic Progress</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Access Transcripts</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Schedule Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-calendar-check fs-1 text-success mb-3"></i>
                            <h4 class="card-title">Class Schedule</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>View Class Timetable</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Exam Schedules</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Course Planning</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Communication -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-bell fs-1 text-warning mb-3"></i>
                            <h4 class="card-title">Announcements</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>School Updates</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Important Deadlines</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Event Notifications</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Online Services -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-file-text fs-1 text-danger mb-3"></i>
                            <h4 class="card-title">Online Forms</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Document Requests</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Leave Applications</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Online Submissions</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Enrollment -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-person-plus fs-1 text-info mb-3"></i>
                            <h4 class="card-title">Enrollment</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Online Registration</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Subject Enrollment</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Payment Management</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Student Profile -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-person-badge fs-1 text-secondary mb-3"></i>
                            <h4 class="card-title">Student Profile</h4>
                            <ul class="list-unstyled mt-3 text-start">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Personal Information</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Academic History</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Achievement Records</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="container">
            <div class="copyright text-center">
                Student Information System
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
