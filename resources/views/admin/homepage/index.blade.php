@extends('layouts.admin')

@section('title', 'Home Page CMS Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-1">Home Page CMS Manager</h1>
            <p class="text-muted small mb-0">Control and customize every section, text, image, and label on the public landing page in real time.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Preview Live Home Page
            </a>
            <a href="{{ route('admin.parking-services.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-card-checklist me-1"></i> Manage Services ({{ $servicesCount }})
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-bottom pt-3 pb-0 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="cmsTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold text-dark" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                        <i class="bi bi-badge-ad me-1 text-primary"></i> 1. Branding & Header
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button" role="tab">
                        <i class="bi bi-image me-1 text-warning"></i> 2. Hero Section
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab">
                        <i class="bi bi-grid-fill me-1 text-success"></i> 3. Core Features
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
                        <i class="bi bi-card-checklist me-1 text-info"></i> 4. Services Section
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="how-tab" data-bs-toggle="tab" data-bs-target="#how" type="button" role="tab">
                        <i class="bi bi-list-ol me-1 text-secondary"></i> 5. How It Works
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="locations-tab" data-bs-toggle="tab" data-bs-target="#locations" type="button" role="tab">
                        <i class="bi bi-geo-alt-fill me-1 text-danger"></i> 6. Parking Locations
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner" type="button" role="tab">
                        <i class="bi bi-megaphone me-1 text-warning"></i> 7. Promo Banner
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">
                        <i class="bi bi-info-circle me-1 text-dark"></i> 8. Footer & Contacts
                    </button>
                </li>
            </ul>
        </div>

        <form action="{{ route('admin.homepage.update') }}" method="POST">
            @csrf
            <div class="card-body p-4 bg-light">
                <div class="tab-content" id="cmsTabsContent">
                    
                    <!-- TAB 1: BRANDING & HEADER -->
                    <div class="tab-pane fade show active" id="branding" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-brush text-primary me-2"></i>Header Logo & Identity</h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Brand Logo Letter / Mark</label>
                                    <input type="text" name="site_brand_mark" class="form-control" maxlength="3" value="{{ setting('site_brand_mark', 'P') }}">
                                    <div class="form-text">Single letter icon inside the dark square.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Brand First Word</label>
                                    <input type="text" name="site_brand_first" class="form-control" value="{{ setting('site_brand_first', 'Smart') }}">
                                    <div class="form-text">Dark primary font (e.g. "Smart").</div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted">Brand Second Word (Yellow Highlight)</label>
                                    <input type="text" name="site_brand_second" class="form-control" value="{{ setting('site_brand_second', 'Parking') }}">
                                    <div class="form-text">Accent amber font (e.g. "Parking").</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Brand Tagline (Under Logo)</label>
                                    <input type="text" name="app_tagline" class="form-control" value="{{ setting('app_tagline', 'Park Smarter. Live Easier.') }}">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-4 shadow-sm border">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-compass text-primary me-2"></i>Navigation Menu Labels</h5>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-muted">Nav 1</label>
                                    <input type="text" name="nav_home_label" class="form-control" value="{{ setting('nav_home_label', 'Home') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-muted">Nav 2</label>
                                    <input type="text" name="nav_services_label" class="form-control" value="{{ setting('nav_services_label', 'Services') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Nav 3</label>
                                    <input type="text" name="nav_how_it_works_label" class="form-control" value="{{ setting('nav_how_it_works_label', 'How It Works') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Nav 4</label>
                                    <input type="text" name="nav_locations_label" class="form-control" value="{{ setting('nav_locations_label', 'Parking Locations') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-muted">Nav 5</label>
                                    <input type="text" name="nav_about_label" class="form-control" value="{{ setting('nav_about_label', 'About') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: HERO SECTION -->
                    <div class="tab-pane fade" id="hero" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-display text-warning me-2"></i>Hero Headlines & Action</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Hero Title Line 1</label>
                                    <input type="text" name="hero_title_line1" class="form-control" value="{{ setting('hero_title_line1', 'Find & Book') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Hero Title Line 2</label>
                                    <input type="text" name="hero_title_line2" class="form-control" value="{{ setting('hero_title_line2', 'Parking') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Highlight Word (Amber)</label>
                                    <input type="text" name="hero_title_highlight" class="form-control" value="{{ setting('hero_title_highlight', 'Instantly') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Hero Subtitle / Description</label>
                                    <textarea name="hero_subtitle" class="form-control" rows="3">{{ setting('hero_subtitle', 'Smart parking for a smarter tomorrow. Save time, avoid hassle and book your parking slot in just a few clicks.') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">CTA Button Text</label>
                                    <input type="text" name="hero_btn_text" class="form-control" value="{{ setting('hero_btn_text', 'Find Parking') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Full-Width Panorama Image Path</label>
                                    <input type="text" name="hero_bg_image" class="form-control" value="{{ setting('hero_bg_image', 'images/landing/hero-panorama.png') }}">
                                    <div class="form-text">Relative path inside public folder. Default: images/landing/hero-panorama.png</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: CORE FEATURES (4 CARDS) -->
                    <div class="tab-pane fade" id="features" role="tabpanel">
                        <div class="row g-3">
                            <!-- Feature 1 -->
                            <div class="col-md-6">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-1-circle text-success me-1"></i> Feature 1</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon (Bootstrap Icon Class)</label>
                                        <input type="text" name="feature_1_icon" class="form-control" value="{{ setting('feature_1_icon', 'bi bi-lightning-charge-fill') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="feature_1_title" class="form-control" value="{{ setting('feature_1_title', 'Quick Booking') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="feature_1_desc" class="form-control" value="{{ setting('feature_1_desc', 'Book in seconds') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="col-md-6">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-2-circle text-success me-1"></i> Feature 2</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon (Bootstrap Icon Class)</label>
                                        <input type="text" name="feature_2_icon" class="form-control" value="{{ setting('feature_2_icon', 'bi bi-shield-check') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="feature_2_title" class="form-control" value="{{ setting('feature_2_title', 'Secure & Safe') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="feature_2_desc" class="form-control" value="{{ setting('feature_2_desc', 'Trusted parking spaces') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="col-md-6">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-3-circle text-success me-1"></i> Feature 3</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon (Bootstrap Icon Class)</label>
                                        <input type="text" name="feature_3_icon" class="form-control" value="{{ setting('feature_3_icon', 'bi bi-geo-alt-fill') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="feature_3_title" class="form-control" value="{{ setting('feature_3_title', 'Multiple Locations') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="feature_3_desc" class="form-control" value="{{ setting('feature_3_desc', 'Across the city') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 4 -->
                            <div class="col-md-6">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-4-circle text-success me-1"></i> Feature 4</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon (Bootstrap Icon Class)</label>
                                        <input type="text" name="feature_4_icon" class="form-control" value="{{ setting('feature_4_icon', 'bi bi-car-front-fill') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="feature_4_title" class="form-control" value="{{ setting('feature_4_title', 'Hassle Free') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="feature_4_desc" class="form-control" value="{{ setting('feature_4_desc', 'Park with confidence') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: SERVICES SECTION -->
                    <div class="tab-pane fade" id="services" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-card-checklist text-info me-2"></i>Services Section Headers</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Section Tag</label>
                                    <input type="text" name="services_section_tag" class="form-control" value="{{ setting('services_section_tag', 'OUR SERVICES') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted">Heading</label>
                                    <input type="text" name="services_section_title" class="form-control" value="{{ setting('services_section_title', 'Parking Services for Everyone') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">View Link Text</label>
                                    <input type="text" name="services_view_all_text" class="form-control" value="{{ setting('services_view_all_text', 'View All Services') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Section Description</label>
                                    <input type="text" name="services_section_desc" class="form-control" value="{{ setting('services_section_desc', 'Choose the right parking solution for your needs.') }}">
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Dynamic Database Services</h6>
                                    <p class="text-muted small mb-0">Services displayed on the homepage are pulled dynamically from the database. Currently configured: <strong>{{ $servicesCount }} services</strong>.</p>
                                </div>
                                <a href="{{ route('admin.parking-services.index') }}" class="btn btn-primary btn-sm px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Open Services CRUD
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: HOW IT WORKS (3 STEPS) -->
                    <div class="tab-pane fade" id="how" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-ol text-secondary me-2"></i>Section Heading</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Section Title</label>
                                    <input type="text" name="how_it_works_title" class="form-control" value="{{ setting('how_it_works_title', 'How It Works') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Section Subtext</label>
                                    <input type="text" name="how_it_works_desc" class="form-control" value="{{ setting('how_it_works_desc', 'Parking made simple in 3 easy steps.') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Step 1 -->
                            <div class="col-md-4">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><span class="badge bg-warning text-dark me-1">1</span> Step 1</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon</label>
                                        <input type="text" name="step_1_icon" class="form-control" value="{{ setting('step_1_icon', 'bi bi-search') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="step_1_title" class="form-control" value="{{ setting('step_1_title', 'Search Location') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="step_1_desc" class="form-control" value="{{ setting('step_1_desc', 'Find a parking space near you') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="col-md-4">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><span class="badge bg-warning text-dark me-1">2</span> Step 2</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon</label>
                                        <input type="text" name="step_2_icon" class="form-control" value="{{ setting('step_2_icon', 'bi bi-calendar3') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="step_2_title" class="form-control" value="{{ setting('step_2_title', 'Book Your Slot') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="step_2_desc" class="form-control" value="{{ setting('step_2_desc', 'Choose date and time') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="col-md-4">
                                <div class="bg-white p-4 rounded-4 shadow-sm border h-100">
                                    <h6 class="fw-bold text-dark mb-3"><span class="badge bg-warning text-dark me-1">3</span> Step 3</h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Icon</label>
                                        <input type="text" name="step_3_icon" class="form-control" value="{{ setting('step_3_icon', 'bi bi-car-front-fill') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted">Title</label>
                                        <input type="text" name="step_3_title" class="form-control" value="{{ setting('step_3_title', 'Park & Go') }}">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted">Description</label>
                                        <input type="text" name="step_3_desc" class="form-control" value="{{ setting('step_3_desc', 'Arrive and enjoy a hassle-free experience') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: PARKING LOCATIONS -->
                    <div class="tab-pane fade" id="locations" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Locations Section Headers</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Section Tag</label>
                                    <input type="text" name="locations_section_tag" class="form-control" value="{{ setting('locations_section_tag', 'PARKING LOCATIONS') }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted">Heading</label>
                                    <input type="text" name="locations_section_title" class="form-control" value="{{ setting('locations_section_title', 'Available Parking Facilities') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Map Button Text</label>
                                    <input type="text" name="locations_view_map_text" class="form-control" value="{{ setting('locations_view_map_text', 'View Live Map') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Section Description</label>
                                    <input type="text" name="locations_section_desc" class="form-control" value="{{ setting('locations_section_desc', 'Real-time capacity and verified locations across the city.') }}">
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Live Database Locations</h6>
                                    <p class="text-muted small mb-0">Active parking locations, real-time slot counters, and hourly tariffs are directly synchronized from MySQL. Currently registered: <strong>{{ $locationsCount }} locations</strong>.</p>
                                </div>
                                <a href="{{ route('admin.parking-locations.index') }}" class="btn btn-primary btn-sm px-3">
                                    <i class="bi bi-building me-1"></i> Manage Locations
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 7: PROMOTIONAL BANNER -->
                    <div class="tab-pane fade" id="banner" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-megaphone text-warning me-2"></i>Banner Content</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Badge Tag</label>
                                    <input type="text" name="banner_tag" class="form-control" value="{{ setting('banner_tag', 'SMART PARKING') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Heading Title</label>
                                    <input type="text" name="banner_title" class="form-control" value="{{ setting('banner_title', 'A Cleaner, Smarter City') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Button Text</label>
                                    <input type="text" name="banner_btn_text" class="form-control" value="{{ setting('banner_btn_text', 'Book Parking Now') }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-muted">Description</label>
                                    <input type="text" name="banner_desc" class="form-control" value="{{ setting('banner_desc', "Let's make urban living easier, together.") }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Car Image Path</label>
                                    <input type="text" name="banner_image" class="form-control" value="{{ setting('banner_image', 'images/landing/banner-car.png') }}">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-4 shadow-sm border">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-check2-circle text-success me-2"></i>Benefit Bullet Points</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Benefit 1</label>
                                    <input type="text" name="banner_bullet_1" class="form-control" value="{{ setting('banner_bullet_1', 'Less Traffic') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Benefit 2</label>
                                    <input type="text" name="banner_bullet_2" class="form-control" value="{{ setting('banner_bullet_2', 'Cleaner Environment') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Benefit 3</label>
                                    <input type="text" name="banner_bullet_3" class="form-control" value="{{ setting('banner_bullet_3', 'Happier Communities') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 8: FOOTER & CONTACTS -->
                    <div class="tab-pane fade" id="footer" role="tabpanel">
                        <div class="bg-white p-4 rounded-4 shadow-sm border">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-envelope text-dark me-2"></i>Contact Details & Copyright</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Support Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ setting('contact_email', 'support@smartparking.com') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Phone Number</label>
                                    <input type="text" name="contact_phone" class="form-control" value="{{ setting('contact_phone', '+1 (800) 555-PARK') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Copyright Notice</label>
                                    <input type="text" name="footer_copyright_text" class="form-control" value="{{ setting('footer_copyright_text', 'All Rights Reserved.') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Physical Address</label>
                                    <input type="text" name="contact_address" class="form-control" value="{{ setting('contact_address', '100 Innovation Blvd, Tech District, Suite 400') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-white border-top p-4 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Changes will immediately appear on the public landing page upon saving.
                </span>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="bi bi-check-lg me-1"></i> Save Homepage Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
