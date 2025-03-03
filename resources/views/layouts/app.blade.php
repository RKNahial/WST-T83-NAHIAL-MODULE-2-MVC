<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>@yield('title') | Student Information System</title>
        <meta content="" name="description">
        <meta content="" name="keywords">


        
        <!-- Favicons -->
        <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
        <link href="{{ asset('assets/img/user.png') }}" rel="icon">
        <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <style>
            /* Force hide the footer */
            .dataTable-bottom,
            .dataTable-info,
            .dataTable-pagination,
            .datatable-bottom,
            .datatable tfoot,
            .datatable-pagination,
            .datatable-info {
                display: none !important;
            }
            
            /* Make all row paddings consistent */
            .datatable tbody tr td,
            .table tbody tr td {
                padding: 0.75rem !important;
            }
            
            /* Remove bottom border and keep consistent padding */
            .datatable tbody tr:last-child td,
            .table tbody tr:last-child td {
                border-bottom: none !important;
                padding: 0.75rem !important;  /* Same padding as other rows */
            }

            /* Remove extra spacing at table bottom */
            .datatable,
            .table {
                margin-bottom: 0 !important;
            }

            /* Adjust card body padding */
            .card-body {
                padding-bottom: 1.5rem !important;
            }
        </style>
    </head>
    <body>
        @include('partials.header')
        
        @include('partials.sidebar')
        
        <main id="main" class="main">
            <div class="pagetitle">
                <h1>@yield('title')</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </nav>
            </div>
            
            @yield('content')
        </main>

        <!-- Vendor JS Files -->
        <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
        <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
        <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

        <!-- Template Main JS File -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        <!-- Then DataTables -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

        @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Remove or modify this part as it might be conflicting
                const tables = document.querySelectorAll(".datatable");
                tables.forEach(table => {
                    new simpleDatatables.DataTable(table, {
                        perPageSelect: false,
                        searchable: false,
                        footer: false,
                        paging: false,
                        info: false
                    });
                });

                // Keep the alert auto-dismiss functionality
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert:not(.alert-important)');
                    alerts.forEach(function(alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 2500);
            });
        </script>
        @endpush

        @stack('scripts')
    </body>
</html>