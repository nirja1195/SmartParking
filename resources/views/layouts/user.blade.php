<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartPark AI') - User Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #2563EB;
            --dark-blue: #172554;
            --light-blue: #EFF6FF;
            --bg-main: #F8FAFC;
            --success-green: #16A34A;
            --warning-amber: #F59E0B;
            --danger-red: #DC2626;
        }
        body {
            background-color: var(--bg-main);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-user {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .navbar-brand {
            font-weight: 800;
            color: var(--dark-blue) !important;
            letter-spacing: -0.02em;
        }
        .navbar-brand i {
            color: var(--primary-blue);
        }
        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 0.5rem;
            transition: all 0.15s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-blue) !important;
            background-color: var(--light-blue);
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        /* Visual Slot Grid Color Standards */
        .slot-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 85px;
            border-radius: 0.5rem;
            font-weight: 700;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
        }
        .slot-available {
            background-color: #dcfce7;
            border-color: #86efac;
            color: #15803d;
        }
        .slot-available:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
            background-color: #bbf7d0;
            color: #166534;
        }
        .slot-reserved {
            background-color: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
            cursor: not-allowed;
            opacity: 0.85;
        }
        .slot-occupied {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
            cursor: not-allowed;
            opacity: 0.85;
        }
        .slot-maintenance {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #64748b;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .slot-blocked {
            background-color: #334155;
            border-color: #1e293b;
            color: #f8fafc;
            cursor: not-allowed;
            opacity: 0.9;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Requirement #11 & #48: Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-user sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('user.dashboard') }}">
                <i class="bi bi-p-square-fill fs-3 me-2"></i>
                <span>SMARTPARK AI</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.find-parking*') || request()->routeIs('user.parking*') ? 'active' : '' }}" href="{{ route('user.find-parking') }}">
                            <i class="bi bi-geo-alt me-1"></i> Find Parking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.my-bookings*') ? 'active' : '' }}" href="{{ route('user.my-bookings') }}">
                            <i class="bi bi-ticket-detailed me-1"></i> My Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.vehicles*') ? 'active' : '' }}" href="{{ route('user.vehicles') }}">
                            <i class="bi bi-car-front me-1"></i> My Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.notifications') ? 'active' : '' }}" href="{{ route('user.notifications') }}">
                            <i class="bi bi-bell me-1"></i> Notifications
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-weight: bold; font-size: 0.8rem;">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span>{{ auth()->user()->name ?? 'Customer' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.settings') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4 flex-grow-1">
        @include('components.alert')
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        <div class="container">
            SMARTPARK AI &copy; {{ date('Y') }} &bull; AI-Powered Smart Parking Management System
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
