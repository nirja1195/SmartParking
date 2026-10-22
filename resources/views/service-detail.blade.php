<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} - {{ setting('app_name', 'SmartParking') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: #101828;
            background-color: #FAFAFA;
        }
        .site-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid #F1F5F9;
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .brand-logo-mark {
            width: 38px;
            height: 38px;
            background: #111827;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.35rem;
            color: #F9B91E;
        }
        .nav-link-custom {
            color: #334155;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
            text-decoration: none;
        }
        .nav-link-custom.active {
            color: #0F172A;
            font-weight: 700;
            position: relative;
        }
        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 28px;
            height: 3px;
            background-color: #F9B91E;
            border-radius: 2px;
        }
        .service-hero-header {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 3rem 0 2rem;
        }
        .service-detail-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .site-footer {
            background-color: #101828;
            color: #94A3B8;
            padding: 3rem 0 1.5rem;
            font-size: 0.88rem;
            margin-top: 4rem;
        }
        .site-footer a {
            color: #CBD5E1;
            text-decoration: none;
        }
        .site-footer a:hover {
            color: #F9B91E;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="site-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none gap-2">
                <div class="brand-logo-mark">{{ setting('site_brand_mark', 'P') }}</div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ setting('site_brand_first', 'Smart') }}<span class="text-warning">{{ setting('site_brand_second', 'Parking') }}</span></h5>
                    <small class="text-muted" style="font-size: 0.68rem;">{{ setting('app_tagline', 'Park Smarter. Live Easier.') }}</small>
                </div>
            </a>
            <nav class="d-none d-md-flex align-items-center gap-2">
                <a href="{{ route('home') }}" class="nav-link-custom">Home</a>
                <a href="{{ route('services.index') }}" class="nav-link-custom active">Services</a>
                <a href="{{ route('home') }}#how-it-works" class="nav-link-custom">How It Works</a>
                <a href="{{ route('home') }}#locations" class="nav-link-custom">Parking Locations</a>
                <a href="{{ route('about') }}" class="nav-link-custom">About</a>
            </nav>
            <div>
                @auth('web')
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                @else
                    <a href="javascript:void(0)" onclick="openAuthModal('login', 'dashboard')" class="btn btn-outline-secondary btn-sm rounded-circle p-2">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Breadcrumb & Header -->
    <section class="service-hero-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-decoration-none text-muted">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="{{ $service->icon ?: 'bi bi-car-front-fill' }} fs-3 text-dark"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold text-dark mb-0">{{ $service->name }}</h1>
                    <p class="text-muted mb-0">{{ $service->short_description ?: $service->description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Detail Content -->
    <main class="container my-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="service-detail-card p-4">
                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="img-fluid rounded-4 mb-4 w-100" style="max-height: 380px; object-fit: cover;">
                    <h4 class="fw-bold mb-3">About {{ $service->name }}</h4>
                    <p class="text-secondary" style="line-height: 1.7; font-size: 1.05rem;">
                        {{ $service->description }}
                    </p>
                    <p class="text-secondary" style="line-height: 1.7;">
                        Enjoy seamless automated entry, real-time bay guidance, safe 24/7 monitored facilities, and contactless digital payment. Book in advance or park instantly at our verified locations.
                    </p>

                    <div class="mt-4 pt-3 border-top d-flex gap-3">
                        <a href="{{ route('user.find-parking') }}" class="btn btn-warning fw-bold text-dark px-4 py-2.5 rounded-3">
                            <i class="bi bi-geo-alt-fill me-1"></i> Book Parking For This Service
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-3 py-2.5 rounded-3">
                            <i class="bi bi-arrow-left me-1"></i> Back to All Services
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3">Key Features</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-direction-column gap-3">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <span>24/7 Security & CCTV Surveillance</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-phone text-primary fs-5"></i>
                            <span>Mobile QR Ticket & Pass Entry</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
                            <span>Instant Live Availability</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-credit-card text-info fs-5"></i>
                            <span>Contactless Online Payments</span>
                        </li>
                    </ul>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3">Other Services</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($relatedServices as $rel)
                        <a href="{{ route('services.show', $rel->slug) }}" class="text-decoration-none d-flex align-items-center justify-content-between p-2 rounded-3 hover-bg">
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $rel->icon }} text-warning"></i>
                                <span class="fw-semibold text-dark">{{ $rel->name }}</span>
                            </div>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container text-center">
            <p class="mb-0 text-secondary">&copy; {{ date('Y') }} {{ setting('site_brand_first', 'Smart') }}{{ setting('site_brand_second', 'Parking') }}. All rights reserved.</p>
        </div>
    </footer>

    @include('partials.auth-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
