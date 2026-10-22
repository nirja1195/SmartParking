@extends('layouts.admin')

@section('title', 'System Settings & CMS')
@section('page-title', 'System Settings & Content Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Configuration & Landing CMS</h4>
        <p class="text-muted mb-0">Manage global operational policies, pricing formulas, platform details, and homepage content dynamically.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs card-header-tabs m-0 px-3" id="settingsTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-3 fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                            <i class="bi bi-sliders me-1 text-primary"></i> General
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 fw-semibold" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button">
                            <i class="bi bi-cash-coin me-1 text-success"></i> Pricing & Rules
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 fw-semibold" id="cms-tab" data-bs-toggle="tab" data-bs-target="#cms" type="button">
                            <i class="bi bi-layout-text-window-reverse me-1 text-info"></i> Homepage CMS
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <input type="hidden" name="group" value="general">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Platform / Application Name</label>
                                    <input type="text" class="form-control" name="app_name" value="{{ setting('app_name', 'SMARTPARK AI') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tagline</label>
                                    <input type="text" class="form-control" name="app_tagline" value="{{ setting('app_tagline', 'AI-Powered Smart Parking Management System') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Default Currency Code</label>
                                    <select name="currency" class="form-select">
                                        <option value="USD" {{ setting('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ setting('currency') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="INR" {{ setting('currency') === 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                        <option value="GBP" {{ setting('currency') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Support Contact Email</label>
                                    <input type="email" class="form-control" name="contact_email" value="{{ setting('contact_email', 'support@smartpark.com') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Support Contact Phone</label>
                                    <input type="text" class="form-control" name="contact_phone" value="{{ setting('contact_phone', '+1 (800) 555-PARK') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Headquarters Address</label>
                                    <input type="text" class="form-control" name="contact_address" value="{{ setting('contact_address', '100 Innovation Way, Silicon Valley, CA') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Rules Tab -->
                        <div class="tab-pane fade" id="pricing" role="tabpanel">
                            <input type="hidden" name="group" value="pricing">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Platform Convenience Fee ($)</label>
                                    <input type="number" step="0.5" class="form-control" name="convenience_fee" value="{{ setting('convenience_fee', '2.00') }}" required>
                                    <small class="text-muted">Flat booking fee charged on reservations</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Overtime Grace Window (Minutes)</label>
                                    <input type="number" class="form-control" name="grace_minutes" value="{{ setting('grace_minutes', '15') }}" required>
                                    <small class="text-muted">Departure grace period before penalties apply</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Overtime Hourly Multiplier</label>
                                    <input type="number" step="0.1" class="form-control" name="overtime_multiplier" value="{{ setting('overtime_multiplier', '1.5') }}" required>
                                    <small class="text-muted">Hourly charge multiplier applied to overstay hours</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Cancellation Grace Period (Minutes)</label>
                                    <input type="number" class="form-control" name="cancellation_window" value="{{ setting('cancellation_window', '30') }}" required>
                                    <small class="text-muted">Minutes before booking start time for free cancellation</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Min Booking Duration (Hours)</label>
                                    <input type="number" class="form-control" name="min_booking_hours" value="{{ setting('min_booking_hours', '1') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Max Booking Duration (Hours)</label>
                                    <input type="number" class="form-control" name="max_booking_hours" value="{{ setting('max_booking_hours', '24') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Homepage CMS Tab -->
                        <div class="tab-pane fade" id="cms" role="tabpanel">
                            <input type="hidden" name="group" value="cms">
                            
                            <!-- Header & Hero -->
                            <div class="border-bottom pb-3 mb-3">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-window me-1 text-primary"></i> Hero Section CMS</h6>
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold small">Hero Headline</label>
                                        <input type="text" class="form-control" name="hero_title" value="{{ setting('hero_title', 'Find & Book Parking Instantly') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Hero CTA Button Text</label>
                                        <input type="text" class="form-control" name="hero_btn_text" value="{{ setting('hero_btn_text', 'Find Parking') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Hero Subtitle</label>
                                        <textarea class="form-control" name="hero_subtitle" rows="2">{{ setting('hero_subtitle', 'Smart parking for a smarter tomorrow. Save time, avoid hassle and book your parking slot in just a few clicks.') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 Features -->
                            <div class="border-bottom pb-3 mb-3">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-stars me-1 text-warning"></i> 4 Core Features</h6>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label fw-bold small">Feature 1</label>
                                        <input type="text" class="form-control mb-1" name="feature_1_title" value="{{ setting('feature_1_title', 'Quick Booking') }}">
                                        <input type="text" class="form-control" name="feature_1_desc" value="{{ setting('feature_1_desc', 'Book in seconds') }}">
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label fw-bold small">Feature 2</label>
                                        <input type="text" class="form-control mb-1" name="feature_2_title" value="{{ setting('feature_2_title', 'Secure & Safe') }}">
                                        <input type="text" class="form-control" name="feature_2_desc" value="{{ setting('feature_2_desc', 'Trusted parking spaces') }}">
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label fw-bold small">Feature 3</label>
                                        <input type="text" class="form-control mb-1" name="feature_3_title" value="{{ setting('feature_3_title', 'Multiple Locations') }}">
                                        <input type="text" class="form-control" name="feature_3_desc" value="{{ setting('feature_3_desc', 'Across the city') }}">
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label fw-bold small">Feature 4</label>
                                        <input type="text" class="form-control mb-1" name="feature_4_title" value="{{ setting('feature_4_title', 'Hassle Free') }}">
                                        <input type="text" class="form-control" name="feature_4_desc" value="{{ setting('feature_4_desc', 'Park with confidence') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- How It Works 3 Steps -->
                            <div class="border-bottom pb-3 mb-3">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-diagram-3 me-1 text-info"></i> How It Works (3 Steps)</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Step 1</label>
                                        <input type="text" class="form-control mb-1" name="step_1_title" value="{{ setting('step_1_title', 'Search Location') }}">
                                        <input type="text" class="form-control" name="step_1_desc" value="{{ setting('step_1_desc', 'Find a parking space near you') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Step 2</label>
                                        <input type="text" class="form-control mb-1" name="step_2_title" value="{{ setting('step_2_title', 'Book Your Slot') }}">
                                        <input type="text" class="form-control" name="step_2_desc" value="{{ setting('step_2_desc', 'Choose date and time') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Step 3</label>
                                        <input type="text" class="form-control mb-1" name="step_3_title" value="{{ setting('step_3_title', 'Park & Go') }}">
                                        <input type="text" class="form-control" name="step_3_desc" value="{{ setting('step_3_desc', 'Arrive and enjoy a hassle-free experience') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Promotional Banner -->
                            <div class="mb-3">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-megaphone me-1 text-success"></i> Promotional Banner</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Banner Title</label>
                                        <input type="text" class="form-control" name="banner_title" value="{{ setting('banner_title', 'A Cleaner, Smarter City') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Banner CTA Button Text</label>
                                        <input type="text" class="form-control" name="banner_btn_text" value="{{ setting('banner_btn_text', 'Book Parking Now') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Banner Subtitle</label>
                                        <input type="text" class="form-control" name="banner_desc" value="{{ setting('banner_desc', "Let's make urban living easier, together.") }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Services Management Quick Link -->
                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <div class="fw-bold text-dark"><i class="bi bi-grid-fill me-1 text-warning"></i> Dynamic Services Management</div>
                                    <small class="text-muted">Manage the 5 services cards, images, icons, and display ordering.</small>
                                </div>
                                <a href="{{ route('admin.parking-services.index') }}" class="btn btn-sm btn-outline-dark fw-bold">
                                    Manage Services <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Save Configurations
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-server me-2 text-primary"></i> Environment Status</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Framework:</span>
                        <span class="fw-bold">Laravel {{ app()->version() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">PHP Version:</span>
                        <span class="fw-bold">PHP {{ PHP_VERSION }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Database Engine:</span>
                        <span class="fw-bold">MySQL (smartpark_ai)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Authentication:</span>
                        <span class="fw-bold">Multi-guard (Admin, Staff, Web)</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-1"><i class="bi bi-shield-check text-success me-1"></i> Data Integrity & Dynamic Storage</h6>
                <p class="text-muted small mb-0">
                    All system parameters are persisted directly into the MySQL <code>settings</code> table and loaded dynamically across customer views, pricing calculations, and hero banners.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
