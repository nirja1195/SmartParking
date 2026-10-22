<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Terminal') - SMARTPARK AI</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-staff {
            background-color: #1e293b;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-staff py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('staff.dashboard') }}">
                <i class="bi bi-person-badge-fill text-warning me-2 fs-4"></i>
                <span>SMARTPARK AI &bull; GATE TERMINAL</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('staff.entries.index') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Confirm Entry
                </a>
                <a href="{{ route('staff.exits.index') }}" class="btn btn-sm btn-warning text-dark">
                    <i class="bi bi-box-arrow-right me-1"></i> Confirm Exit
                </a>
                <form method="POST" action="{{ route('staff.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @include('components.alert')
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            SMARTPARK AI Gate Terminal &copy; {{ date('Y') }}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
