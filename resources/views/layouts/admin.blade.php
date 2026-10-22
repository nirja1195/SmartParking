<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parking Locations') - SMARTPARK AI</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sp-yellow: #F9B91E;
            --sp-yellow-light: #FFF8E7;
            --sp-yellow-hover: #FEF0C7;
            --sp-dark: #101828;
            --sp-navy: #0F172A;
            --sp-gray-muted: #64748B;
            --sp-border: #E2E8F0;
            --sp-bg-light: #F8FAFC;
            --sp-green: #10B981;
            --sp-green-light: #DCFCE7;
            --sp-green-text: #15803D;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--sp-bg-light);
            color: var(--sp-dark);
            min-height: 100vh;
        }

        /* Top Header Navbar */
        .admin-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--sp-border);
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-logo-box {
            width: 36px;
            height: 36px;
            background-color: var(--sp-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sp-yellow);
            font-weight: 800;
            font-size: 1.15rem;
        }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--sp-dark);
        }

        .brand-name span {
            color: var(--sp-yellow);
        }

        .brand-tagline {
            font-size: 0.68rem;
            color: var(--sp-gray-muted);
            font-weight: 500;
            letter-spacing: -0.01em;
        }

        .header-divider {
            width: 1px;
            height: 28px;
            background-color: var(--sp-border);
            margin: 0 1rem;
        }

        /* Top Header Search Input */
        .header-search-box {
            position: relative;
            max-width: 280px;
            width: 100%;
        }

        .header-search-box input {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding-left: 2.2rem;
            font-size: 0.85rem;
            height: 36px;
        }

        .header-search-box i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.88rem;
        }

        /* Shared admin navigation */
        .admin-sidebar {
            width: 198px;
            flex-shrink: 0;
            background-color: #FFFFFF;
            border-right: 1px solid var(--sp-border);
            min-height: calc(100vh - 64px);
            height: calc(100dvh - 64px);
            position: sticky;
            top: 64px;
            overflow-y: auto;
            transition: margin-left 0.25s ease-in-out;
        }

        .admin-sidebar.collapsed {
            margin-left: -198px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 11px 2px 11px 11px;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 8.5px;
        }

        .sidebar-item:last-child {
            margin-bottom: 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 21px;
            min-height: 42px;
            width: 100%;
            padding: 7px 14px;
            border: 1px solid transparent;
            border-radius: 4px 0 0 4px;
            background: transparent;
            color: #345DA7;
            font-size: 13px;
            font-weight: 400;
            line-height: 16px;
            text-align: left;
            text-decoration: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .sidebar-link i {
            flex: 0 0 22px;
            width: 22px;
            font-size: 22px;
            line-height: 1;
            color: #000000;
            text-align: center;
        }

        .sidebar-link:hover {
            color: #234B91;
            background-color: #FFF8E7;
        }

        .sidebar-link:focus-visible {
            outline: 2px solid #345DA7;
            outline-offset: -2px;
        }

        .sidebar-link.active {
            color: #000000;
            background-color: #FFF0B8;
            border-left-color: #F9B91E;
            font-weight: 700;
        }
        /* Profile & Dropdowns */
        .profile-btn {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #F1F5F9;
            border: 1px solid var(--sp-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sp-dark);
            font-size: 1.1rem;
        }

        /* Card Styling */
        .card-custom {
            background-color: #FFFFFF;
            border: 1px solid var(--sp-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }

        .yellow-icon-badge {
            width: 44px;
            height: 44px;
            background-color: #FEF3C7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sp-dark);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        /* Location Type Soft Pastel Badges matching screenshot */
        .badge-type-commercial { background-color: #E0F2FE; color: #0369A1; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-airport { background-color: #F3E8FF; color: #6B21A8; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-office { background-color: #FFEDD5; color: #C2410C; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-transit { background-color: #DCFCE7; color: #15803D; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-hospital { background-color: #FCE7F3; color: #9D174D; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-educational { background-color: #FEF3C7; color: #92400E; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-type-public { background-color: #E0F2FE; color: #1D4ED8; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }

        /* Badge Status */
        .badge-status-active { background-color: #DCFCE7; color: #15803D; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }
        .badge-status-inactive { background-color: #E2E8F0; color: #475569; font-weight: 600; padding: 0.35em 0.75em; border-radius: 8px; font-size: 0.78rem; }

        /* Small action icon buttons */
        .btn-action-square {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: none;
            background-color: #F1F5F9;
            color: #475569;
            font-size: 0.88rem;
            transition: all 0.15s ease;
        }

        .btn-action-square:hover {
            background-color: #E2E8F0;
            color: #0F172A;
        }

        .btn-action-square-delete {
            background-color: #FEE2E2;
            color: #EF4444;
        }

        .btn-action-square-delete:hover {
            background-color: #FCA5A5;
            color: #991B1B;
        }

        /* Responsive Layout */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed;
                top: 64px;
                left: 0;
                z-index: 1020;
                height: calc(100vh - 64px);
                margin-left: -198px;
            }
            .admin-sidebar.show {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- 1. TOP HEADER -->
    <header class="admin-header d-flex align-items-center justify-content-between px-3 px-md-4">
        <div class="d-flex align-items-center">
            <!-- Hamburger Menu Button -->
            <button class="btn btn-link text-dark p-0 me-3 fs-4 text-decoration-none" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>

            <!-- SmartParking Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <div class="brand-logo-box me-2">
                    <span>P</span>
                </div>
                <div>
                    <div class="brand-name"><span>Smart</span>Parking</div>
                    <div class="brand-tagline">Smart Parking &bull; Live, Easy.</div>
                </div>
            </a>
        </div>

        <!-- Header Search Bar -->
        <div class="d-none d-md-block header-search-box">
            <i class="bi bi-search"></i>
            <form action="{{ route('admin.locations.index') }}" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search location..." value="{{ request('search') }}">
            </form>
        </div>

        <div class="d-flex align-items-center">
            <!-- Notification Bell -->
            <div class="dropdown me-2 me-md-3">
                <a href="#" class="text-dark position-relative p-2 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-warning text-dark fw-bold" style="font-size: 0.65rem; padding: 0.3em 0.5em;">
                        {{ $unreadNotificationsCount ?? 3 }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-0" style="width: 320px;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                        <h6 class="fw-bold mb-0">Notifications</h6>
                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none small text-primary">View All</a>
                    </div>
                    <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                        <a href="{{ route('admin.notifications.index') }}" class="list-group-item list-group-item-action p-3 text-decoration-none">
                            <div class="fw-semibold small text-dark">High Occupancy Alert</div>
                            <div class="text-muted small text-truncate">City Center Mall capacity reached 80%.</div>
                            <small class="text-muted" style="font-size: 0.7rem;">5 mins ago</small>
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="list-group-item list-group-item-action p-3 text-decoration-none">
                            <div class="fw-semibold small text-dark">New Location Active</div>
                            <div class="text-muted small text-truncate">Airport Parking terminal 2 is live.</div>
                            <small class="text-muted" style="font-size: 0.7rem;">1 hour ago</small>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div class="header-divider"></div>

            <!-- Profile & Dropdown -->
            <div class="dropdown">
                <button class="profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-circle me-2">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="d-none d-sm-block text-start me-2">
                        <div class="fw-bold text-dark" style="font-size: 0.88rem; line-height: 1.2;">
                            {{ auth()->guard('admin')->user()->name ?? 'Admin' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.72rem;">
                            {{ auth()->guard('admin')->user()->role ?? 'Administrator' }}
                        </div>
                    </div>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('admin.profile.index') }}">
                            <i class="bi bi-person me-2 text-warning"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-2 text-secondary"></i> Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Container Layout -->
    <div class="d-flex">
        <!-- 2. SHARED ADMIN SIDEBAR -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div>
                <ul class="sidebar-menu">
                    <!-- 1. Dashboard -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- 2. Parking Locations -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.locations.index') }}" class="sidebar-link {{ request()->routeIs('admin.locations*') || request()->routeIs('admin.parking-locations*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt"></i>
                            <span>Parking Locations</span>
                        </a>
                    </li>

                    <!-- 3. Parking Slots -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.parking-slots.index') }}" class="sidebar-link {{ request()->routeIs('admin.parking-slots*') ? 'active' : '' }}">
                            <i class="bi bi-p-square"></i>
                            <span>Parking Slots</span>
                        </a>
                    </li>

                    <!-- 4. Bookings -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i>
                            <span>Bookings</span>
                        </a>
                    </li>

                    <!-- 5. Users -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                        </a>
                    </li>

                    <!-- 6. Vehicles -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.vehicles.index') }}" class="sidebar-link {{ request()->routeIs('admin.vehicles*') ? 'active' : '' }}">
                            <i class="bi bi-car-front"></i>
                            <span>Vehicles</span>
                        </a>
                    </li>

                    <!-- 7. Payments -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments*') || request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </li>

                    <!-- 8. Reports -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <!-- 9. Notifications -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.notifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>

                    <!-- 10. Settings -->
                    <li class="sidebar-item">
                        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                    <!-- 11. Logout -->
                    <li class="sidebar-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content View -->
        <main class="flex-grow-1 p-3 p-md-4" style="min-width: 0;">
            @include('components.alert')
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.innerWidth < 992) {
                        sidebar.classList.toggle('show');
                    } else {
                        sidebar.classList.toggle('collapsed');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
