<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('app_name', 'SmartParking') }} - {{ setting('app_tagline', 'Park Smarter. Live Easier.') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-yellow: #FFC72C;
            --brand-yellow-dark: #E5A800;
            --brand-yellow-light: #FFFBEB;
            --brand-yellow-badge: #FEF3C7;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-light: #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            background-color: #FFFFFF;
            overflow-x: hidden;
        }

        /* ------------------ Header / Navbar ------------------ */
        .site-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid #F1F5F9;
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.65rem;
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
            color: var(--brand-yellow);
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.1;
            margin: 0;
        }

        .brand-title span {
            color: #F59E0B;
        }

        .brand-subtitle {
            font-size: 0.68rem;
            color: #94A3B8;
            font-weight: 500;
            margin: 0;
            letter-spacing: 0.02em;
        }

        .nav-link-custom {
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.4rem 0.9rem;
            text-decoration: none;
            transition: color 0.2s ease;
            position: relative;
        }

        .nav-link-custom:hover {
            color: #0F172A;
        }

        .nav-link-custom.active {
            color: #0F172A;
            font-weight: 700;
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 3px;
            background-color: var(--brand-yellow-dark);
            border-radius: 2px;
        }

        .header-profile-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #E2E8F0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            background: #FFFFFF;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .header-profile-btn:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            color: #0F172A;
        }

        /* ------------------ Hero Section (Full-Width Panorama) ------------------ */
        .hero-section {
            position: relative;
            overflow: hidden;
            min-height: 560px;
            display: flex;
            align-items: center;
            background-color: #FFFFFF;
        }

        .hero-image,
        .hero-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center right;
            z-index: 1;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0.98) 0%,
                rgba(255, 255, 255, 0.90) 35%,
                rgba(255, 255, 255, 0.25) 65%,
                rgba(255, 255, 255, 0) 100%
            );
            z-index: 2;
            pointer-events: none;
        }

        .hero-content-container {
            position: relative;
            z-index: 3;
            width: 100%;
            padding-top: 3.5rem;
            padding-bottom: 3.5rem;
        }

        .hero-content {
            max-width: 540px;
            position: relative;
            z-index: 3;
        }

        .hero-title {
            font-size: clamp(2.4rem, 4.5vw, 3.85rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.12;
            letter-spacing: -0.025em;
            margin-bottom: 1.25rem;
        }

        .hero-title .highlight-instantly {
            color: #F59E0B;
        }

        .hero-lead {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 2rem;
            max-width: 480px;
        }

        .btn-find-parking-hero {
            background-color: #FFC72C;
            color: #111827;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.85rem 1.85rem;
            border-radius: 12px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(255, 199, 44, 0.45);
            transition: all 0.25s ease;
        }

        .btn-find-parking-hero:hover {
            background-color: #E5A800;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 199, 44, 0.55);
        }

        @media (max-width: 768px) {
            .hero-section {
                min-height: 480px;
            }
            .hero-overlay {
                background: linear-gradient(
                    to right,
                    rgba(255, 255, 255, 0.98) 0%,
                    rgba(255, 255, 255, 0.92) 55%,
                    rgba(255, 255, 255, 0.75) 100%
                );
            }
        }

        /* ------------------ Features Row (4 Items) ------------------ */
        .features-section {
            padding: 2.5rem 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .feature-icon-badge {
            width: 48px;
            height: 48px;
            min-width: 48px;
            background-color: #FEF3C7;
            color: #B45309;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .feature-item-title {
            font-weight: 700;
            font-size: 0.98rem;
            margin-bottom: 0.15rem;
            color: #0F172A;
        }

        .feature-item-desc {
            font-size: 0.82rem;
            color: #64748B;
            margin: 0;
        }

        /* ------------------ Services Section ------------------ */
        .services-section {
            padding: 3.5rem 0;
        }

        .section-tag {
            color: #F59E0B;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
            display: inline-block;
        }

        .section-heading {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 0.35rem;
            letter-spacing: -0.015em;
        }

        .section-subtext {
            color: #64748B;
            font-size: 0.95rem;
            margin: 0;
        }

        .view-all-services-link {
            color: #0F172A;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: color 0.2s ease;
        }

        .view-all-services-link:hover {
            color: #F59E0B;
        }

        .service-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            transition: all 0.25s ease;
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.06);
            border-color: #CBD5E1;
        }

        .service-image-container {
            position: relative;
            height: 120px;
            overflow: hidden;
            background-color: #F1F5F9;
        }

        .service-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .service-circle-icon {
            position: absolute;
            bottom: -16px;
            left: 14px;
            width: 36px;
            height: 36px;
            background: #FFFFFF;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0F172A;
            font-size: 1rem;
            z-index: 2;
        }

        .service-card-body {
            padding: 1.5rem 1rem 1.25rem;
        }

        .service-card-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #0F172A;
            margin-bottom: 0.25rem;
        }

        .service-card-desc {
            font-size: 0.78rem;
            color: #64748B;
            line-height: 1.45;
            margin: 0;
        }

        /* ------------------ How It Works ------------------ */
        .how-it-works-section {
            padding: 3.5rem 0 4rem;
            background-color: #FFFFFF;
        }

        .step-item {
            text-align: center;
            position: relative;
        }

        .step-badge-number {
            width: 26px;
            height: 26px;
            background: #FFC72C;
            color: #000000;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            margin-bottom: 0.65rem;
        }

        .step-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px solid #0F172A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            color: #0F172A;
            margin-bottom: 1rem;
            background: #FFFFFF;
        }

        .step-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #0F172A;
            margin-bottom: 0.35rem;
        }

        .step-desc {
            font-size: 0.85rem;
            color: #64748B;
            max-width: 200px;
            margin: 0 auto;
        }

        .step-arrow-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #CBD5E1;
            margin-top: 2.2rem;
        }

        /* ------------------ Bottom Promotional Banner ------------------ */
        .promo-banner-section {
            padding: 1rem 0 4rem;
        }

        .promo-banner-card {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 50%, #FDE68A 100%);
            border: 1px solid #FDE68A;
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .promo-tag {
            color: #D97706;
            font-weight: 800;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .promo-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .promo-desc {
            color: #475569;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-book-now-banner {
            background-color: #FFC72C;
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem 1.6rem;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);
            transition: all 0.2s ease;
        }

        .btn-book-now-banner:hover {
            background-color: #E5A800;
            color: #000000;
            transform: translateY(-2px);
        }

        .promo-center-image img {
            border-radius: 12px;
            max-width: 100%;
            height: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .promo-benefit-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .promo-benefit-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 600;
            color: #1E293B;
            font-size: 0.95rem;
        }

        .promo-benefit-item i {
            font-size: 1.15rem;
            color: #059669;
        }

        /* ------------------ Dynamic Parking Locations Section ------------------ */
        .locations-section {
            padding: 4.5rem 0 3.5rem;
            background-color: #FAFAFA;
            border-top: 1px solid #F1F5F9;
        }

        .location-facility-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 18px;
            transition: all 0.25s ease;
        }

        .location-facility-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08) !important;
            border-color: #CBD5E1;
        }

        .btn-book-slot-location {
            background-color: #FFC72C;
            color: #101828;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.5rem 1.15rem;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-book-slot-location:hover {
            background-color: #E5A800;
            color: #000000;
            transform: translateY(-1px);
        }

        /* ------------------ Footer ------------------ */
        .site-footer {
            background: #0F172A;
            color: #94A3B8;
            padding: 2.5rem 0;
            font-size: 0.88rem;
            border-top: 1px solid #1E293B;
        }

        .site-footer a {
            color: #CBD5E1;
            text-decoration: none;
        }

        .site-footer a:hover {
            color: var(--brand-yellow);
        }
    </style>
</head>
<body>

    <!-- 1. Header / Navigation -->
    <header class="site-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Brand Logo & Tagline -->
                <a href="{{ route('home') }}" class="brand-logo-container">
                    <div class="brand-logo-mark">{{ setting('site_brand_mark', 'P') }}</div>
                    <div>
                        <div class="brand-title">{{ setting('site_brand_first', 'Smart') }}<span>{{ setting('site_brand_second', 'Parking') }}</span></div>
                        <div class="brand-subtitle">{{ setting('app_tagline', 'Park Smarter. Live Easier.') }}</div>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="d-none d-md-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="nav-link-custom active">{{ setting('nav_home_label', 'Home') }}</a>
                    <a href="{{ route('services.index') }}" class="nav-link-custom">{{ setting('nav_services_label', 'Services') }}</a>
                    <a href="{{ route('how-it-works') }}" class="nav-link-custom">{{ setting('nav_how_it_works_label', 'How It Works') }}</a>
                    <a href="#locations" class="nav-link-custom">{{ setting('nav_locations_label', 'Parking Locations') }}</a>
                    <a href="{{ route('about') }}" class="nav-link-custom">{{ setting('nav_about_label', 'About') }}</a>
                </nav>

                <!-- User Profile / Login Icon -->
                <div>
                    @auth('web')
                        <a href="{{ route('user.dashboard') }}" 
                           class="header-profile-btn" 
                           title="Customer Dashboard">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                    @else
                        <a href="javascript:void(0)" 
                           onclick="openAuthModal('login', 'dashboard')" 
                           class="header-profile-btn" 
                           title="Customer Login">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- 2. Hero Section (Full-Width Background Panorama) -->
    <section class="hero-section">
        <!-- Full-bleed background parking facility image -->
        <img src="{{ asset(setting('hero_bg_image', 'images/landing/hero-panorama.png')) }}" 
             alt="{{ setting('hero_image_alt', 'Smart Parking Facility') }}" 
             class="hero-image hero-bg-image">

        <!-- Soft white gradient overlay on left -->
        <div class="hero-overlay"></div>

        <!-- Left-aligned hero content in container -->
        <div class="container hero-content-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    {!! setting('hero_title_line1', 'Find &amp; Book') !!}<br>
                    {{ setting('hero_title_line2', 'Parking') }} <span class="highlight-instantly">{{ setting('hero_title_highlight', 'Instantly') }}</span>
                </h1>
                <p class="hero-lead">
                    {{ setting('hero_subtitle', 'Smart parking for a smarter tomorrow. Save time, avoid hassle and book your parking slot in just a few clicks.') }}
                </p>

                <!-- FIND PARKING BUTTON: Opens modal if unauthenticated, directly navigates if logged in -->
                <div>
                    @auth('web')
                        <a href="{{ route('user.find-parking') }}" class="btn-find-parking-hero">
                            <i class="bi bi-search"></i>
                            <span>{{ setting('hero_btn_text', 'Find Parking') }}</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="javascript:void(0)" onclick="openAuthModal('login', 'find-parking')" class="btn-find-parking-hero">
                            <i class="bi bi-search"></i>
                            <span>{{ setting('hero_btn_text', 'Find Parking') }}</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Four Feature Cards Section -->
    <section class="features-section">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Feature 1 -->
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon-badge">
                            <i class="{{ setting('feature_1_icon', 'bi bi-lightning-charge-fill') }}"></i>
                        </div>
                        <div>
                            <div class="feature-item-title">{{ setting('feature_1_title', 'Quick Booking') }}</div>
                            <p class="feature-item-desc">{{ setting('feature_1_desc', 'Book in seconds') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon-badge">
                            <i class="{{ setting('feature_2_icon', 'bi bi-shield-check') }}"></i>
                        </div>
                        <div>
                            <div class="feature-item-title">{{ setting('feature_2_title', 'Secure & Safe') }}</div>
                            <p class="feature-item-desc">{{ setting('feature_2_desc', 'Trusted parking spaces') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon-badge">
                            <i class="{{ setting('feature_3_icon', 'bi bi-geo-alt-fill') }}"></i>
                        </div>
                        <div>
                            <div class="feature-item-title">{{ setting('feature_3_title', 'Multiple Locations') }}</div>
                            <p class="feature-item-desc">{{ setting('feature_3_desc', 'Across the city') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon-badge">
                            <i class="{{ setting('feature_4_icon', 'bi bi-car-front-fill') }}"></i>
                        </div>
                        <div>
                            <div class="feature-item-title">{{ setting('feature_4_title', 'Hassle Free') }}</div>
                            <p class="feature-item-desc">{{ setting('feature_4_desc', 'Park with confidence') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Parking Services for Everyone -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                <div>
                    <span class="section-tag">&mdash; {{ setting('services_section_tag', 'OUR SERVICES') }}</span>
                    <h2 class="section-heading">{{ setting('services_section_title', 'Parking Services for Everyone') }}</h2>
                    <p class="section-subtext">{{ setting('services_section_desc', 'Choose the right parking solution for your needs.') }}</p>
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="{{ route('services.index') }}" class="view-all-services-link">
                        {{ setting('services_view_all_text', 'View All Services') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Dynamic Services Cards from Database -->
            <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-5">
                @forelse($services as $service)
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset($service->image ?: 'images/landing/service-car.png') }}" alt="{{ $service->name }}">
                            <div class="service-circle-icon">
                                <i class="{{ $service->icon ?: 'bi bi-car-front-fill' }}"></i>
                            </div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">{{ $service->name }}</div>
                            <p class="service-card-desc">{{ $service->description }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Fallback Service Cards -->
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset('images/landing/service-car.png') }}" alt="Car Parking">
                            <div class="service-circle-icon"><i class="bi bi-car-front-fill"></i></div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">Car Parking</div>
                            <p class="service-card-desc">Safe and secure parking for your car.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset('images/landing/service-bike.png') }}" alt="Bike Parking">
                            <div class="service-circle-icon"><i class="bi bi-bicycle"></i></div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">Bike Parking</div>
                            <p class="service-card-desc">Convenient parking for two-wheelers.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset('images/landing/service-ev.png') }}" alt="EV Charging">
                            <div class="service-circle-icon"><i class="bi bi-ev-station-fill"></i></div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">EV Charging</div>
                            <p class="service-card-desc">Park and charge your electric vehicle.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset('images/landing/service-accessible.png') }}" alt="Accessible Parking">
                            <div class="service-circle-icon"><i class="bi bi-person-wheelchair"></i></div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">Accessible Parking</div>
                            <p class="service-card-desc">Dedicated spaces for differently-abled.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="service-card">
                        <div class="service-image-container">
                            <img src="{{ asset('images/landing/service-hourly.png') }}" alt="Hourly / Daily Parking">
                            <div class="service-circle-icon"><i class="bi bi-clock-fill"></i></div>
                        </div>
                        <div class="service-card-body">
                            <div class="service-card-title">Hourly / Daily Parking</div>
                            <p class="service-card-desc">Flexible plans for short or long stays.</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. How It Works Section -->
    <section class="how-it-works-section" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading mb-1">{{ setting('how_it_works_title', 'How It Works') }}</h2>
                <p class="section-subtext">{{ setting('how_it_works_desc', 'Parking made simple in 3 easy steps.') }}</p>
            </div>

            <div class="row g-4 justify-content-center align-items-center">
                <!-- Step 1 -->
                <div class="col-md-3">
                    <div class="step-item">
                        <div><span class="step-badge-number">1</span></div>
                        <div class="step-icon-circle">
                            <i class="{{ setting('step_1_icon', 'bi bi-search') }}"></i>
                        </div>
                        <div class="step-title">{{ setting('step_1_title', 'Search Location') }}</div>
                        <p class="step-desc">{{ setting('step_1_desc', 'Find a parking space near you') }}</p>
                    </div>
                </div>

                <!-- Arrow 1 -->
                <div class="col-md-1 d-none d-md-block text-center">
                    <div class="step-arrow-connector">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-3">
                    <div class="step-item">
                        <div><span class="step-badge-number">2</span></div>
                        <div class="step-icon-circle">
                            <i class="{{ setting('step_2_icon', 'bi bi-calendar3') }}"></i>
                        </div>
                        <div class="step-title">{{ setting('step_2_title', 'Book Your Slot') }}</div>
                        <p class="step-desc">{{ setting('step_2_desc', 'Choose date and time') }}</p>
                    </div>
                </div>

                <!-- Arrow 2 -->
                <div class="col-md-1 d-none d-md-block text-center">
                    <div class="step-arrow-connector">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-3">
                    <div class="step-item">
                        <div><span class="step-badge-number">3</span></div>
                        <div class="step-icon-circle">
                            <i class="{{ setting('step_3_icon', 'bi bi-car-front-fill') }}"></i>
                        </div>
                        <div class="step-title">{{ setting('step_3_title', 'Park & Go') }}</div>
                        <p class="step-desc">{{ setting('step_3_desc', 'Arrive and enjoy a hassle-free experience') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Parking Locations Section from Database -->
    <section class="locations-section" id="locations">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                <div>
                    <span class="section-tag">&mdash; {{ setting('locations_section_tag', 'PARKING LOCATIONS') }}</span>
                    <h2 class="section-heading">{{ setting('locations_section_title', 'Available Parking Facilities') }}</h2>
                    <p class="section-subtext">{{ setting('locations_section_desc', 'Real-time capacity and verified locations across the city.') }}</p>
                </div>
                <div class="mt-2 mt-md-0">
                    @auth('web')
                        <a href="{{ route('user.find-parking') }}" class="view-all-services-link">
                            {{ setting('locations_view_map_text', 'View Live Map') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="javascript:void(0)" onclick="openAuthModal('login', 'find-parking')" class="view-all-services-link">
                            {{ setting('locations_view_map_text', 'View Live Map') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="row g-4">
                @forelse($locations as $loc)
                    @php
                        $availSlots = $loc->zones->flatMap->slots->where('status', 'Available')->count();
                        $totalSlots = $loc->zones->flatMap->slots->count();
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card location-facility-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold text-dark mb-0">{{ $loc->name }}</h5>
                                <span class="badge {{ $availSlots > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} rounded-pill px-2.5 py-1">
                                    <i class="bi bi-dot"></i> {{ $availSlots }} / {{ $totalSlots }} Available
                                </span>
                            </div>
                            <p class="text-secondary small mb-3">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i> {{ $loc->address }}, {{ $loc->city }}
                            </p>
                            @if($loc->facilities)
                            <div class="mb-3">
                                @foreach(array_slice(explode(',', $loc->facilities), 0, 3) as $fac)
                                    <span class="badge bg-light text-dark border me-1 small">{{ trim($fac) }}</span>
                                @endforeach
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                <div>
                                    <span class="fs-5 fw-bold text-dark">${{ number_format($loc->parking_charges, 2) }}</span>
                                    <span class="text-muted small">/ hour</span>
                                </div>
                                @auth('web')
                                    <a href="{{ route('user.parking.show', $loc->id) }}" class="btn-book-slot-location">
                                        Book Slot <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0)" onclick="openAuthModal('login', 'find-parking')" class="btn-book-slot-location">
                                        Book Slot <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">No parking locations registered yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 6. Bottom Promotional Banner Section -->
    <section class="promo-banner-section" id="about">
        <div class="container">
            <div class="promo-banner-card">
                <div class="row align-items-center g-4">
                    <!-- Left: Promotional Copy -->
                    <div class="col-lg-5">
                        <div class="promo-tag">{{ setting('banner_tag', 'SMART PARKING') }}</div>
                        <h2 class="promo-title">{{ setting('banner_title', 'A Cleaner, Smarter City') }}</h2>
                        <p class="promo-desc">{{ setting('banner_desc', "Let's make urban living easier, together.") }}</p>
                        @auth('web')
                        <a href="{{ route('user.find-parking') }}" class="btn-book-now-banner">
                            {{ setting('banner_btn_text', 'Book Parking Now') }} <i class="bi bi-arrow-right"></i>
                        </a>
                        @else
                        <a href="javascript:void(0)" onclick="openAuthModal('login', 'find-parking')" class="btn-book-now-banner">
                            {{ setting('banner_btn_text', 'Book Parking Now') }} <i class="bi bi-arrow-right"></i>
                        </a>
                        @endauth
                    </div>

                    <!-- Center: Top-down Car Visual -->
                    <div class="col-md-6 col-lg-4 text-center promo-center-image">
                        <img src="{{ asset(setting('banner_image', 'images/landing/banner-car.png')) }}" alt="Parked vehicle" class="img-fluid">
                    </div>

                    <!-- Right: Benefits Bullet List -->
                    <div class="col-md-6 col-lg-3">
                        <ul class="promo-benefit-list">
                            <li class="promo-benefit-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ setting('banner_bullet_1', 'Less Traffic') }}</span>
                            </li>
                            <li class="promo-benefit-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ setting('banner_bullet_2', 'Cleaner Environment') }}</span>
                            </li>
                            <li class="promo-benefit-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ setting('banner_bullet_3', 'Happier Communities') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Public Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <span class="fw-bold text-white">{{ setting('site_brand_first', 'Smart') }}{{ setting('site_brand_second', 'Parking') }}</span> &copy; {{ date('Y') }} &bull; {{ setting('footer_copyright_text', 'All Rights Reserved.') }}
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="small text-secondary">{{ setting('contact_email', 'support@smartparking.com') }} &bull; {{ setting('contact_phone', '+1 (800) 555-PARK') }}</span>
                    <a href="{{ route('admin.login') }}" class="ms-3 text-secondary text-decoration-none small"><i class="bi bi-shield-lock me-1"></i>Admin Portal</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- SmartParking Authentication Modal (Login / Register / Forgot Password) -->
    @include('partials.auth-modal')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
