<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') - SmartPark</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="auth-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2 mb-2" style="width: 48px; height: 48px;">
                                <i class="bi bi-p-square-fill fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">SmartPark</h3>
                        </a>
                        <p class="text-muted small mt-1">@yield('subtitle', 'Smart Parking Management System')</p>
                    </div>

                    @include('components.alert')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
