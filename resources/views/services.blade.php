<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->seo_title ?: 'Parking Services - SmartParking AI' }}</title>
    <meta name="description" content="{{ $settings->seo_description ?: 'Explore our full suite of smart parking solutions including car parking, bike bays, EV charging, accessible spots, and premium parking.' }}">
    <meta name="keywords" content="{{ $settings->seo_keywords ?: 'parking services, EV charging, accessible parking, premium parking, smart park' }}">
    <meta property="og:title" content="{{ $settings->seo_title }}">
    <meta property="og:description" content="{{ $settings->seo_description }}">
    <meta property="og:image" content="{{ asset($settings->og_image ?: $settings->hero_image) }}">

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
            --primary: #F9B91E;
            --primary-dark: #E5A800;
            --primary-light: #FFFBEB;
            --primary-badge: #FEF3C7;
            --text-dark: #101828;
            --text-muted: #64748B;
            --border-light: #E2E8F0;
            --background: #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            background-color: var(--background);
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
            color: var(--primary);
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
            width: 28px;
            height: 3px;
            background-color: var(--primary);
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

        /* ------------------ Services Hero Section ------------------ */
        .services-hero-section {
            position: relative;
            background-color: #FFFFFF;
            overflow: hidden;
            min-height: 290px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #F1F5F9;
        }

        .services-hero-bg-layer {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 65%;
            background-image: url("{{ asset($settings->hero_image) }}");
            background-size: cover;
            background-position: center right;
            z-index: 1;
        }

        .services-hero-gradient-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to right,
                #FFFFFF 0%,
                #FFFFFF 40%,
                rgba(255, 255, 255, 0.92) 52%,
                rgba(255, 255, 255, 0) 75%
            );
            z-index: 2;
            pointer-events: none;
        }

        .services-hero-content {
            position: relative;
            z-index: 3;
            max-width: 540px;
            padding: 3rem 0;
        }

        .services-eyebrow-dash {
            display: inline-block;
            width: 28px;
            height: 3.5px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .services-eyebrow-text {
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #1E293B;
            text-transform: uppercase;
        }

        .services-hero-title {
            font-size: clamp(2.2rem, 3.8vw, 3.25rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.12;
            letter-spacing: -0.025em;
        }

        .services-hero-title .highlight-yellow {
            color: var(--primary);
        }

        .services-hero-lead {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.55;
            max-width: 440px;
        }

        /* ------------------ Services Grid Section ------------------ */
        .services-catalog-section {
            padding: 3.5rem 0 2rem;
        }

        .services-search-wrapper {
            position: relative;
            max-width: 320px;
            width: 100%;
        }

        .services-search-wrapper i {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.95rem;
            pointer-events: none;
        }

        .services-search-input {
            border-radius: 9999px;
            border: 1px solid #E2E8F0;
            padding: 0.62rem 1.25rem 0.62rem 2.6rem;
            font-size: 0.9rem;
            background-color: #FFFFFF;
            transition: all 0.2s ease;
        }

        .services-search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 185, 30, 0.2);
            outline: none;
        }

        /* ------------------ Service Card (Exact Layout) ------------------ */
        .service-card-item {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            overflow: visible;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .service-card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06);
            border-color: #CBD5E1;
        }

        .service-card-img-box {
            position: relative;
            width: 100%;
            height: 138px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            overflow: hidden;
            background-color: #F8FAFC;
        }

        .service-card-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .service-card-item:hover .service-card-img-box img {
            transform: scale(1.03);
        }

        /* Overlapping yellow circular badge */
        .service-icon-badge {
            position: absolute;
            bottom: -18px;
            left: 18px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #101828;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            border: 3px solid #FFFFFF;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .service-card-content {
            padding: 1.65rem 1.25rem 1.25rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 0.85rem;
            flex-grow: 1;
        }

        .service-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 0.25rem;
        }

        .service-card-desc {
            font-size: 0.86rem;
            color: #64748B;
            line-height: 1.45;
            margin: 0;
        }

        .service-circle-action-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0F172A;
            font-size: 1rem;
            text-decoration: none;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .service-card-item:hover .service-circle-action-btn {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #000000;
            transform: translateX(2px);
        }

        /* ------------------ Custom Parking Solution Banner ------------------ */
        .custom-solution-banner {
            background-color: #FEF9C3;
            border: 1px solid #FEF08A;
            border-radius: 20px;
            padding: 1.85rem 2.25rem;
            margin-top: 3.5rem;
            margin-bottom: 2.75rem;
        }

        .custom-banner-icon-badge {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: #FDE047;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #1E293B;
            flex-shrink: 0;
        }

        .custom-banner-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 0.2rem;
            letter-spacing: -0.015em;
        }

        .custom-banner-desc {
            font-size: 0.92rem;
            color: #475569;
            margin: 0;
        }

        .btn-custom-contact {
            background-color: var(--primary);
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.65rem 1.6rem;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(249, 185, 30, 0.35);
            white-space: nowrap;
        }

        .btn-custom-contact:hover {
            background-color: var(--primary-dark);
            color: #000000;
            transform: translateY(-1px);
        }

        /* ------------------ Feature Strip (4 Items) ------------------ */
        .feature-strip-section {
            padding: 1rem 0 3.5rem;
        }

        .feature-strip-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .feature-strip-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #FEF3C7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #1E293B;
            flex-shrink: 0;
        }

        .feature-strip-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: #0F172A;
            margin-bottom: 0.1rem;
        }

        .feature-strip-desc {
            font-size: 0.82rem;
            color: #64748B;
            margin: 0;
        }

        .feature-col-border {
            border-right: 1px solid #E2E8F0;
        }

        @media (max-width: 991px) {
            .feature-col-border {
                border-right: none;
                border-bottom: 1px solid #F1F5F9;
                padding-bottom: 1rem;
                margin-bottom: 0.5rem;
            }
            .services-hero-bg-layer {
                width: 80%;
                opacity: 0.6;
            }
        }

        /* ------------------ Dark Footer ------------------ */
        .site-footer {
            background-color: #101828;
            color: #94A3B8;
            padding: 4rem 0 1.5rem;
            font-size: 0.88rem;
        }

        .site-footer a {
            color: #CBD5E1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .site-footer a:hover {
            color: var(--primary);
        }

        .footer-heading {
            font-weight: 700;
            font-size: 0.95rem;
            color: #FFFFFF;
            margin-bottom: 1.15rem;
        }

        .footer-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .footer-social-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #CBD5E1;
            font-size: 1.15rem;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .footer-social-icon:hover {
            color: var(--primary);
            transform: translateY(-2px);
        }

        .app-badge-btn img {
            height: 34px;
            width: auto;
            border-radius: 6px;
            transition: opacity 0.2s ease;
        }

        .app-badge-btn:hover img {
            opacity: 0.85;
        }

        .footer-bottom-bar {
            margin-top: 3.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #1E293B;
            font-size: 0.82rem;
            color: #64748B;
        }

        .footer-tagline-dash {
            display: inline-block;
            width: 26px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 2px;
            vertical-align: middle;
            margin-right: 0.4rem;
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

                <!-- Navigation Links with Services Active -->
                <nav class="d-none d-md-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="nav-link-custom">{{ setting('nav_home_label', 'Home') }}</a>
                    <a href="{{ route('services.index') }}" class="nav-link-custom active">{{ setting('nav_services_label', 'Services') }}</a>
                    <a href="{{ route('how-it-works') }}" class="nav-link-custom">{{ setting('nav_how_it_works_label', 'How It Works') }}</a>
                    <a href="{{ route('home') }}#locations" class="nav-link-custom">{{ setting('nav_locations_label', 'Parking Locations') }}</a>
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

    <!-- 2. Services Hero Section (Full-Width Panorama Background Layer) -->
    <section class="services-hero-section">
        <!-- Right-aligned background layer with soft white fade on left -->
        <div class="services-hero-bg-layer"></div>
        <div class="services-hero-gradient-overlay"></div>

        <!-- Left Content in container -->
        <div class="container position-relative" style="z-index: 3;">
            <div class="services-hero-content">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="services-eyebrow-dash"></span>
                    <span class="services-eyebrow-text">{{ $settings->hero_eyebrow }}</span>
                </div>
                <h1 class="services-hero-title mb-3">
                    {{ $settings->hero_title }}<br>
                    {{ $settings->hero_title_line2 ?? 'For' }} <span class="highlight-yellow">{{ $settings->hero_highlight }}</span>
                </h1>
                <p class="services-hero-lead mb-0">
                    {{ $settings->hero_description }}
                </p>
            </div>
        </div>
    </section>

    <!-- 3. Our Parking Services Grid Section with Real-Time Search -->
    <section class="services-catalog-section">
        <div class="container">
            <!-- Section Header & Search Bar -->
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 1.85rem; letter-spacing: -0.02em;">
                        {{ $settings->services_section_title }}
                    </h2>
                    <p class="text-secondary mb-0" style="font-size: 0.95rem;">
                        {{ $settings->services_section_description }}
                    </p>
                </div>
                <div class="services-search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" 
                           id="servicesSearchInput" 
                           class="form-control services-search-input" 
                           placeholder="{{ $settings->search_placeholder }}" 
                           aria-label="Search services"
                           autocomplete="off">
                </div>
            </div>

            <!-- Dynamic 3x2 Service Card Grid -->
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3" id="servicesGridContainer">
                @forelse($services as $service)
                <div class="col service-card-wrapper" data-name="{{ strtolower($service->name) }}" data-desc="{{ strtolower($service->description) }}">
                    <div class="service-card-item">
                        <!-- Top image box -->
                        <div class="service-card-img-box">
                            <img src="{{ asset($service->image) }}" alt="{{ $service->name }}">
                            <!-- Circular yellow icon badge overlapping the image bottom-left -->
                            <div class="service-icon-badge">
                                <i class="{{ $service->icon ?: 'bi bi-car-front-fill' }}"></i>
                            </div>
                        </div>

                        <!-- Card text and right arrow action -->
                        <div class="service-card-content">
                            <div>
                                <h5 class="service-card-title">{{ $service->name }}</h5>
                                <p class="service-card-desc">{{ $service->description }}</p>
                            </div>
                            <div>
                                <a href="{{ $service->button_url ?: route('services.show', $service->slug) }}" 
                                   class="service-circle-action-btn" 
                                   title="{{ $service->button_text ?: 'Explore' }}">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted">
                    No active parking services available at the moment.
                </div>
                @endforelse

                <!-- Real-time search "No services found" message -->
                <div id="noServicesFound" class="col-12 text-center py-5 d-none">
                    <div class="text-secondary">
                        <i class="bi bi-search fs-1 mb-2 d-block text-muted"></i>
                        <h6 class="fw-bold text-dark">No services found.</h6>
                        <p class="small text-muted mb-0">Try searching with a different keyword like "car", "bike", or "EV".</p>
                    </div>
                </div>
            </div>

            <!-- 4. Custom Parking Solution Banner (Exact UI Match) -->
            <div class="custom-solution-banner">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="custom-banner-icon-badge">
                            <i class="{{ $settings->custom_banner_icon ?: 'bi bi-building' }}"></i>
                        </div>
                        <div>
                            <h4 class="custom-banner-title">{{ $settings->custom_banner_title }}</h4>
                            <p class="custom-banner-desc">{{ $settings->custom_banner_description }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ $settings->custom_banner_button_url }}" class="btn-custom-contact">
                            <span>{{ $settings->custom_banner_button_text }}</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. Feature Strip (4 Dynamic Items with Vertical Dividers) -->
            <div class="feature-strip-section">
                <div class="row g-4 align-items-center">
                    @foreach($features as $index => $feat)
                    <div class="col-6 col-lg-3 {{ !$loop->last ? 'feature-col-border' : '' }}">
                        <div class="feature-strip-item ps-lg-2">
                            <div class="feature-strip-icon-circle">
                                <i class="{{ $feat->icon }}"></i>
                            </div>
                            <div>
                                <div class="feature-strip-title">{{ $feat->title }}</div>
                                <p class="feature-strip-desc">{{ $feat->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- 6. Dark Footer (Matching Exact Screenshot Hierarchy) -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Col 1: Brand & Socials -->
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('home') }}" class="brand-logo-container mb-3 d-inline-flex">
                        <div class="brand-logo-mark">{{ setting('site_brand_mark', 'P') }}</div>
                        <div>
                            <div class="brand-title text-white">{{ setting('site_brand_first', 'Smart') }}<span class="text-warning">{{ setting('site_brand_second', 'Parking') }}</span></div>
                            <div class="brand-subtitle text-secondary">{{ setting('app_tagline', 'Park Smarter. Live Easier.') }}</div>
                        </div>
                    </a>
                    <p class="text-secondary small mb-4 pe-lg-4" style="line-height: 1.6;">
                        {{ setting('footer_about_text', 'Smart Parking makes urban living easier with safe, secure and convenient parking solutions.') }}
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ setting('footer_social_fb', '#') }}" class="footer-social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="{{ setting('footer_social_ig', '#') }}" class="footer-social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="{{ setting('footer_social_tw', '#') }}" class="footer-social-icon" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="{{ setting('footer_social_li', '#') }}" class="footer-social-icon" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="{{ setting('footer_social_yt', '#') }}" class="footer-social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-heading">Quick Links</h6>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('services.index') }}">Services</a></li>
                        <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                        <li><a href="{{ route('home') }}#locations">Parking Locations</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('admin.login') }}"><i class="bi bi-shield-lock me-1"></i>Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Col 3: Support -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-heading">Support</h6>
                    <ul class="footer-links-list">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 4: Download App -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Download Our App</h6>
                    <p class="text-secondary small mb-3">Book parking on the go.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="app-badge-btn" title="Download on Google Play">
                            <img src="{{ asset('images/services/google-play-badge.png') }}" alt="Google Play">
                        </a>
                        <a href="#" class="app-badge-btn" title="Download on App Store">
                            <img src="{{ asset('images/services/app-store-badge.png') }}" alt="App Store">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="footer-bottom-bar d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    &copy; {{ date('Y') }} {{ setting('site_brand_first', 'Smart') }}{{ setting('site_brand_second', 'Parking') }}. All rights reserved.
                </div>
                <div>
                    <span class="footer-tagline-dash"></span>
                    <span class="text-white fw-semibold">{{ setting('footer_tagline', 'A Smarter Way to Park') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- SmartParking Authentication Modal (Login / Register / Forgot Password) -->
    @include('partials.auth-modal')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Instant Real-Time Client-Side Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('servicesSearchInput');
            const cardWrappers = document.querySelectorAll('.service-card-wrapper');
            const noResultsMsg = document.getElementById('noServicesFound');

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.trim().toLowerCase();
                    let visibleCount = 0;

                    cardWrappers.forEach(card => {
                        const name = card.getAttribute('data-name') || '';
                        const desc = card.getAttribute('data-desc') || '';

                        if (query === '' || name.includes(query) || desc.includes(query)) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (visibleCount === 0) {
                        noResultsMsg.classList.remove('d-none');
                    } else {
                        noResultsMsg.classList.add('d-none');
                    }
                });
            }
        });
    </script>
</body>
</html>
