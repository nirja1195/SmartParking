<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageSetting->seo_title ?: 'How It Works - SmartParking AI' }}</title>
    <meta name="description" content="{{ $pageSetting->seo_description ?: 'Discover how easy parking is with SmartParking AI. 4 simple steps to reserve your spot.' }}">
    <meta name="keywords" content="{{ $pageSetting->seo_keywords ?: 'how it works, parking booking, contactless parking, smart parking app' }}">
    <meta property="og:title" content="{{ $pageSetting->seo_title }}">
    <meta property="og:description" content="{{ $pageSetting->seo_description }}">
    <meta property="og:image" content="{{ asset($pageSetting->og_image ?: $pageSetting->hero_image) }}">

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

        /* ------------------ Hero Section ------------------ */
        .hiw-hero-section {
            position: relative;
            background-color: #FFFFFF;
            overflow: hidden;
            min-height: 340px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #F1F5F9;
        }

        .hiw-hero-bg-layer {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 65%;
            background-image: url("{{ asset($pageSetting->hero_image) }}");
            background-size: cover;
            background-position: center right;
            z-index: 1;
        }

        .hiw-hero-gradient-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to right,
                #FFFFFF 0%,
                #FFFFFF 42%,
                rgba(255, 255, 255, 0.95) 54%,
                rgba(255, 255, 255, 0) 78%
            );
            z-index: 2;
            pointer-events: none;
        }

        .hiw-hero-content {
            position: relative;
            z-index: 3;
            max-width: 540px;
            padding: 3.5rem 0;
        }

        .hiw-eyebrow-dash {
            display: inline-block;
            width: 28px;
            height: 3.5px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .hiw-eyebrow-text {
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #1E293B;
            text-transform: uppercase;
        }

        .hiw-hero-title {
            font-size: clamp(2.2rem, 3.8vw, 3.25rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.12;
            letter-spacing: -0.025em;
        }

        .hiw-hero-title .highlight-yellow {
            color: var(--primary);
        }

        .hiw-hero-lead {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.55;
            max-width: 460px;
        }

        /* ------------------ Steps Grid Section ------------------ */
        .hiw-steps-section {
            padding: 4.5rem 0 3.5rem;
            background: #FFFFFF;
        }

        .steps-container-row {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            position: relative;
        }

        .step-card-col {
            flex: 1 1 0;
            min-width: 0;
            padding: 0 10px;
            display: flex;
            flex-direction: column;
        }

        .step-arrow-col {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            margin-top: 50px;
        }

        .step-arrow-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .step-card {
            background: #FFFFFF;
            border: 1px solid #EEF2F6;
            border-radius: 16px;
            padding: 14px 14px 22px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            border-color: #E2E8F0;
        }

        .step-image-wrap {
            position: relative;
            width: 100%;
            height: 145px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
            background-color: #F1F5F9;
        }

        .step-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .step-card:hover .step-image-wrap img {
            transform: scale(1.04);
        }

        .step-number-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #111827;
            font-weight: 800;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            z-index: 2;
        }

        .step-floating-icon-badge {
            position: absolute;
            bottom: -18px;
            left: 50%;
            transform: translateX(-50%);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #111827;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            border: 2.5px solid #FFFFFF;
            z-index: 3;
        }

        .step-title {
            font-size: 1.08rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.45rem;
        }

        .step-description {
            font-size: 0.84rem;
            color: #64748B;
            line-height: 1.5;
            margin: 0;
            padding: 0 4px;
        }

        /* ------------------ App CTA Banner ------------------ */
        .hiw-cta-section {
            padding: 2.5rem 0 4.5rem;
        }

        .hiw-cta-card {
            background: #FFFBEB;
            border: 1px solid #FEF3C7;
            border-radius: 28px;
            padding: 3.5rem 3.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.06);
        }

        /* Ambient yellow circular backdrop */
        .hiw-cta-circle-bg {
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: #FEE7A8;
            top: 50%;
            left: 48%;
            transform: translate(-50%, -50%);
            z-index: 1;
            opacity: 0.65;
            pointer-events: none;
        }

        .hiw-cta-phone-wrap {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hiw-cta-phone-img {
            max-width: 250px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 14px 24px rgba(15, 23, 42, 0.15));
            transition: transform 0.3s ease;
        }

        .hiw-cta-phone-img:hover {
            transform: translateY(-5px);
        }

        .hiw-cta-badge-dash {
            display: inline-block;
            width: 24px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .hiw-cta-badge-text {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #1E293B;
            text-transform: uppercase;
        }

        .hiw-cta-title {
            font-size: clamp(1.8rem, 2.8vw, 2.4rem);
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }

        .hiw-cta-lead {
            font-size: 0.95rem;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 1.75rem;
            max-width: 440px;
        }

        .app-badge-btn {
            display: inline-block;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .app-badge-btn:hover {
            transform: translateY(-2px);
            opacity: 0.92;
        }

        .app-badge-btn img {
            height: 42px;
            width: auto;
        }

        /* CTA Features list on the right */
        .cta-feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.4rem;
            position: relative;
            z-index: 2;
        }

        .cta-feature-item:last-child {
            margin-bottom: 0;
        }

        .cta-feature-icon {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            background-color: #FEF3C7;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            border: 1px solid rgba(249, 185, 30, 0.35);
        }

        .cta-feature-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.2rem;
        }

        .cta-feature-desc {
            font-size: 0.82rem;
            color: #64748B;
            line-height: 1.45;
            margin: 0;
        }

        /* ------------------ FAQ Section ------------------ */
        .hiw-faq-section {
            padding: 4rem 0 5rem;
            background-color: #FFFFFF;
            border-top: 1px solid #F1F5F9;
        }

        .faq-accordion .accordion-item {
            border: 1px solid #E2E8F0;
            border-radius: 12px !important;
            margin-bottom: 0.9rem;
            overflow: hidden;
            background: #FFFFFF;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }

        .faq-accordion .accordion-item:hover {
            border-color: #CBD5E1;
        }

        .faq-accordion .accordion-button {
            font-weight: 700;
            font-size: 0.98rem;
            color: #1E293B;
            padding: 1.15rem 1.4rem;
            background-color: #FFFFFF;
            box-shadow: none !important;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            color: #0F172A;
            background-color: #FFFBEB;
            border-bottom: 1px solid #FEF3C7;
        }

        .faq-accordion .accordion-button::after {
            background-size: 1rem;
            transition: transform 0.2s ease-in-out;
        }

        .faq-accordion .accordion-body {
            padding: 1.25rem 1.4rem;
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.65;
            background-color: #FFFFFF;
        }

        /* ------------------ Footer ------------------ */
        .site-footer {
            background-color: #0B0F17;
            color: #94A3B8;
            padding: 4.5rem 0 2rem;
            font-size: 0.92rem;
        }

        .site-footer a {
            color: #94A3B8;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .site-footer a:hover {
            color: #FFFFFF;
        }

        .footer-heading {
            color: #FFFFFF;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 1.35rem;
            letter-spacing: -0.01em;
        }

        .footer-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links-list li {
            margin-bottom: 0.75rem;
        }

        .footer-social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #1E293B;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            transition: all 0.2s ease;
        }

        .footer-social-icon:hover {
            background-color: var(--primary);
            color: #111827;
        }

        .footer-bottom-bar {
            border-top: 1px solid #1E293B;
            padding-top: 2rem;
            margin-top: 3.5rem;
            font-size: 0.85rem;
        }

        .footer-tagline-dash {
            display: inline-block;
            width: 24px;
            height: 2px;
            background-color: var(--primary);
            margin-right: 0.5rem;
            vertical-align: middle;
        }

        /* ------------------ Responsive Adjustments ------------------ */
        @media (max-width: 991.98px) {
            .steps-container-row {
                flex-wrap: wrap;
            }
            .step-card-col {
                flex: 0 0 50%;
                margin-bottom: 1.5rem;
            }
            .step-arrow-col {
                display: none;
            }
            .hiw-hero-bg-layer {
                opacity: 0.25;
                width: 100%;
            }
            .hiw-cta-circle-bg {
                display: none;
            }
            .hiw-cta-card {
                padding: 2.5rem 1.5rem;
            }
            .hiw-cta-phone-wrap {
                margin: 2rem 0;
            }
        }

        @media (max-width: 575.98px) {
            .step-card-col {
                flex: 0 0 100%;
            }
            .hiw-hero-content {
                padding: 2.5rem 0;
            }
            .hiw-cta-card {
                padding: 2rem 1.25rem;
            }
        }
    </style>
</head>
<body>

    <!-- ==================== Header ==================== -->
    <header class="site-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Brand Logo Area -->
                <a href="{{ route('home') }}" class="brand-logo-container">
                    <div class="brand-logo-mark">
                        {{ setting('site_brand_mark', 'P') }}
                    </div>
                    <div>
                        <h1 class="brand-title">
                            {{ setting('site_brand_first', 'Smart') }}<span>{{ setting('site_brand_second', 'Parking') }}</span>
                        </h1>
                        <p class="brand-subtitle">{{ setting('site_brand_tag', 'PARKING RESERVATION') }}</p>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="d-none d-lg-flex align-items-center gap-1">
                    <a href="{{ route('home') }}" class="nav-link-custom">Home</a>
                    <a href="{{ route('services.index') }}" class="nav-link-custom">Services</a>
                    <a href="{{ route('how-it-works') }}" class="nav-link-custom active">How It Works</a>
                    <a href="{{ route('home') }}#pricing" class="nav-link-custom">Pricing</a>
                    <a href="{{ route('home') }}#locations" class="nav-link-custom">Locations</a>
                    <a href="{{ route('about') }}" class="nav-link-custom">About</a>
                </nav>

                <!-- User Profile / Auth Button -->
                <div class="d-flex align-items-center gap-2">
                    @auth('web')
                        <a href="{{ route('user.dashboard') }}" class="header-profile-btn" title="My Account">
                            <i class="bi bi-person-fill fs-5"></i>
                        </a>
                    @else
                        <button type="button" class="header-profile-btn" onclick="openAuthModal('login', 'dashboard')" title="Login / Register">
                            <i class="bi bi-person fs-5"></i>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ==================== Hero Section ==================== -->
    <section class="hiw-hero-section">
        <div class="hiw-hero-bg-layer"></div>
        <div class="hiw-hero-gradient-overlay"></div>
        <div class="container">
            <div class="hiw-hero-content">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="hiw-eyebrow-dash"></span>
                    <span class="hiw-eyebrow-text">{{ $pageSetting->hero_eyebrow ?: 'HOW IT WORKS' }}</span>
                </div>
                <h1 class="hiw-hero-title mb-3">
                    {{ $pageSetting->hero_title ?: 'Parking Made' }}
                    @if($pageSetting->hero_highlight)
                        <span class="highlight-yellow">{{ $pageSetting->hero_highlight }}</span>
                    @endif
                </h1>
                <p class="hiw-hero-lead mb-0">
                    {{ $pageSetting->hero_description ?: 'Booking your parking space has never been easier. Follow our simple four-step process to secure your spot in seconds.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- ==================== Steps Process (4 Cards with Arrows) ==================== -->
    <section class="hiw-steps-section">
        <div class="container">
            <div class="steps-container-row">
                @foreach($steps as $index => $step)
                    {{-- Step Card --}}
                    <div class="step-card-col">
                        <div class="step-card">
                            <div class="step-image-wrap">
                                <span class="step-number-badge">{{ $step->step_number }}</span>
                                <img src="{{ asset($step->image ?: 'images/how-it-works/step-01.png') }}" alt="{{ $step->title }}">
                                <div class="step-floating-icon-badge">
                                    <i class="{{ $step->icon ?: 'bi-check-circle' }}"></i>
                                </div>
                            </div>
                            <h3 class="step-title">{{ $step->title }}</h3>
                            <p class="step-description">{{ $step->description }}</p>
                        </div>
                    </div>

                    {{-- Right Arrow between steps (not after the last card) --}}
                    @if(!$loop->last)
                        <div class="step-arrow-col">
                            <div class="step-arrow-circle">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== App Download CTA Banner ==================== -->
    <section class="hiw-cta-section">
        <div class="container">
            <div class="hiw-cta-card">
                <!-- Circular background glow behind phone -->
                <div class="hiw-cta-circle-bg"></div>

                <div class="row align-items-center position-relative" style="z-index: 2;">
                    <!-- Left: Text & Badges -->
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="hiw-cta-badge-dash"></span>
                            <span class="hiw-cta-badge-text">{{ $cta->eyebrow ?: 'SMARTER PARKING' }}</span>
                        </div>
                        <h2 class="hiw-cta-title">
                            {{ $cta->title ?: 'Your Next Parking Spot is Just a Few Taps Away!' }}
                        </h2>
                        <p class="hiw-cta-lead">
                            {{ $cta->description ?: 'Download the SmartParking app to easily search, reserve, and navigate to parking spaces anytime, anywhere. Experience seamless contactless parking today.' }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a href="{{ $cta->google_play_url ?: '#' }}" class="app-badge-btn" target="_blank" title="Download on Google Play">
                                <img src="{{ asset('images/services/google-play-badge.png') }}" alt="Google Play">
                            </a>
                            <a href="{{ $cta->app_store_url ?: '#' }}" class="app-badge-btn" target="_blank" title="Download on App Store">
                                <img src="{{ asset('images/services/app-store-badge.png') }}" alt="App Store">
                            </a>
                        </div>
                    </div>

                    <!-- Center: Phone Mockup -->
                    <div class="col-lg-3 text-center mb-4 mb-lg-0">
                        <div class="hiw-cta-phone-wrap">
                            <img src="{{ asset($cta->main_image ?: 'images/how-it-works/cta-phone.png') }}" alt="SmartParking App Mockup" class="hiw-cta-phone-img">
                        </div>
                    </div>

                    <!-- Right: Features List -->
                    <div class="col-lg-4 ps-lg-4">
                        @foreach($ctaFeatures as $feature)
                            <div class="cta-feature-item">
                                <div class="cta-feature-icon">
                                    <i class="{{ $feature->icon }}"></i>
                                </div>
                                <div>
                                    <h4 class="cta-feature-title">{{ $feature->title }}</h4>
                                    <p class="cta-feature-desc">{{ $feature->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FAQ Accordion Section ==================== -->
    <section class="hiw-faq-section" id="faq">
        <div class="container">
            <div class="row g-5">
                <!-- Left Column: Title & Info -->
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="hiw-eyebrow-dash"></span>
                        <span class="hiw-eyebrow-text">{{ $pageSetting->faq_eyebrow ?: 'FAQ' }}</span>
                    </div>
                    <h2 class="hiw-hero-title mb-3" style="font-size: clamp(2rem, 3.2vw, 2.75rem);">
                        {{ $pageSetting->faq_title ?: 'Frequently' }}
                        @if($pageSetting->faq_highlight)
                            <span class="highlight-yellow">{{ $pageSetting->faq_highlight }}</span>
                        @endif
                    </h2>
                    <p class="text-secondary mb-4" style="line-height: 1.6; max-width: 420px;">
                        {{ $pageSetting->faq_description ?: 'Got questions about how SmartParking works? Find clear answers below or contact our 24/7 support team.' }}
                    </p>
                    <div class="d-inline-flex align-items-center gap-2 p-3 rounded-4 bg-light border">
                        <div class="rounded-circle bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-headset fs-5 text-dark"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">Need additional help?</div>
                            <div class="text-muted small">Our team is available 24/7.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Accordion -->
                <div class="col-lg-7">
                    <div class="accordion faq-accordion" id="howItWorksFaqAccordion">
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="headingFaq{{ $faq->id }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq{{ $faq->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapseFaq{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h3>
                                <div id="collapseFaq{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="headingFaq{{ $faq->id }}" data-bs-parent="#howItWorksFaqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== Footer ==================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Col 1: Brand Info -->
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('home') }}" class="brand-logo-container mb-3 d-inline-flex">
                        <div class="brand-logo-mark">
                            {{ setting('site_brand_mark', 'P') }}
                        </div>
                        <div>
                            <h3 class="brand-title text-white">
                                {{ setting('site_brand_first', 'Smart') }}<span>{{ setting('site_brand_second', 'Parking') }}</span>
                            </h3>
                            <p class="brand-subtitle text-secondary">{{ setting('site_brand_tag', 'PARKING RESERVATION') }}</p>
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
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('services.index') }}">Services</a></li>
                        <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                        <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                        <li><a href="{{ route('home') }}#locations">Locations</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('admin.login') }}"><i class="bi bi-shield-lock me-1"></i>Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Col 3: Support -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h4 class="footer-heading">Support</h4>
                    <ul class="footer-links-list">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 4: Download App -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-heading">Download Our App</h4>
                    <p class="text-secondary small mb-3">Book parking on the go.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ $cta->play_store_url ?: '#' }}" class="app-badge-btn" title="Download on Google Play">
                            <img src="{{ asset('images/services/google-play-badge.png') }}" alt="Google Play">
                        </a>
                        <a href="{{ $cta->app_store_url ?: '#' }}" class="app-badge-btn" title="Download on App Store">
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
</body>
</html>
