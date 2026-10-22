@extends('layouts.admin')

@section('title', 'Manage How It Works Page CMS')
@section('page-title', 'How It Works CMS')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-1">How It Works CMS</h1>
            <p class="text-muted small mb-0"><span class="badge bg-warning bg-opacity-25 text-dark me-1">Dynamic Page CMS</span> Control public hero section, the 4-step process cards, app CTA banner, and FAQ accordion.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('how-it-works') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Public Page
            </a>
            <button type="button" class="btn btn-warning fw-bold text-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createStepModal">
                <i class="bi bi-plus-circle me-1"></i> Add Step
            </button>
            <button type="button" class="btn btn-dark fw-bold btn-sm" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                <i class="bi bi-question-circle me-1"></i> Add FAQ
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Please check the form errors below.
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $activeTab = request('tab', 'steps');
    @endphp

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-bottom pt-3 pb-0 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="howItWorksTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'steps' ? 'active' : '' }} fw-semibold text-dark" id="steps-tab" data-bs-toggle="tab" data-bs-target="#stepsTab" type="button" role="tab">
                        <i class="bi bi-123 me-1 text-warning"></i> 1. Steps Process ({{ count($steps) }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'settings' ? 'active' : '' }} fw-semibold text-dark" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settingsTab" type="button" role="tab">
                        <i class="bi bi-layout-text-window-reverse me-1 text-primary"></i> 2. Page & Hero Settings
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'cta' ? 'active' : '' }} fw-semibold text-dark" id="cta-tab" data-bs-toggle="tab" data-bs-target="#ctaTab" type="button" role="tab">
                        <i class="bi bi-phone me-1 text-success"></i> 3. App CTA Banner
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'faqs' ? 'active' : '' }} fw-semibold text-dark" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqsTab" type="button" role="tab">
                        <i class="bi bi-patch-question me-1 text-info"></i> 4. FAQs ({{ count($faqs) }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="howItWorksTabsContent">
                
                {{-- TAB 1: STEPS --}}
                <div class="tab-pane fade {{ $activeTab === 'steps' ? 'show active' : '' }}" id="stepsTab" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Workflow Steps</h5>
                        <button type="button" class="btn btn-warning btn-sm fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#createStepModal">
                            <i class="bi bi-plus-lg me-1"></i> Add Step
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Step #</th>
                                    <th style="width: 100px;">Image</th>
                                    <th>Title & Description</th>
                                    <th style="width: 120px;">Icon</th>
                                    <th style="width: 90px;">Order</th>
                                    <th style="width: 90px;">Status</th>
                                    <th style="width: 120px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($steps as $step)
                                    <tr>
                                        <td>
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1 rounded-circle fs-6" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $step->step_number }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($step->image)
                                                <img src="{{ asset($step->image) }}" alt="{{ $step->title }}" class="rounded-3 border" style="width: 70px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="badge bg-secondary">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $step->title }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 350px;">{{ $step->description }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="{{ $step->icon }} me-1 text-warning"></i> {{ $step->icon }}
                                            </span>
                                        </td>
                                        <td>{{ $step->sort_order }}</td>
                                        <td>
                                            <span class="badge {{ $step->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $step->status }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editStepModal{{ $step->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.how-it-works.steps.destroy', $step->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete Step {{ $step->step_number }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit Step Modal --}}
                                    <div class="modal fade" id="editStepModal{{ $step->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.how-it-works.steps.update', $step->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title fw-bold">Edit Step {{ $step->step_number }}: {{ $step->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">Step Number <span class="text-danger">*</span></label>
                                                                <input type="text" name="step_number" class="form-control" value="{{ old('step_number', $step->step_number) }}" required placeholder="e.g. 01">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                                                <input type="text" name="title" class="form-control" value="{{ old('title', $step->title) }}" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">Sort Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $step->sort_order) }}">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label fw-semibold">Description</label>
                                                                <textarea name="description" class="form-control" rows="3">{{ old('description', $step->description) }}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Bootstrap Icon Class</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="{{ $step->icon }}"></i></span>
                                                                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $step->icon) }}" placeholder="e.g. bi-search">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Active" {{ $step->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $step->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Image Path / URL</label>
                                                                <input type="text" name="image" class="form-control" value="{{ old('image', $step->image) }}" placeholder="images/how-it-works/step-01.png">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Or Upload New Image</label>
                                                                <input type="file" name="image_file" class="form-control" accept="image/*">
                                                            </div>
                                                            @if($step->image)
                                                                <div class="col-12">
                                                                    <div class="small text-muted mb-1">Current Preview:</div>
                                                                    <img src="{{ asset($step->image) }}" alt="Preview" class="rounded border p-1" style="max-height: 80px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Update Step</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No steps created yet. Click "Add Step" above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: PAGE SETTINGS & HERO --}}
                <div class="tab-pane fade {{ $activeTab === 'settings' ? 'show active' : '' }}" id="settingsTab" role="tabpanel">
                    <form action="{{ route('admin.how-it-works.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 text-dark">Hero Section Content</h5>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Eyebrow / Badge Text <span class="text-danger">*</span></label>
                                <input type="text" name="hero_eyebrow" class="form-control" value="{{ old('hero_eyebrow', $settings->hero_eyebrow) }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Main Heading Title <span class="text-danger">*</span></label>
                                <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $settings->hero_title) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Highlighted Word (Yellow)</label>
                                <input type="text" name="hero_highlight" class="form-control" value="{{ old('hero_highlight', $settings->hero_highlight) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Hero Description</label>
                                <textarea name="hero_description" class="form-control" rows="3">{{ old('hero_description', $settings->hero_description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hero Image Path</label>
                                <input type="text" name="hero_image" class="form-control" value="{{ old('hero_image', $settings->hero_image) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Or Upload New Hero Background</label>
                                <input type="file" name="hero_image_file" class="form-control" accept="image/*">
                            </div>
                            @if($settings->hero_image)
                                <div class="col-12">
                                    <div class="small text-muted mb-1">Current Hero Image Preview:</div>
                                    <img src="{{ asset($settings->hero_image) }}" alt="Hero Background" class="rounded border p-1" style="max-height: 120px; width: 100%; object-fit: cover;">
                                </div>
                            @endif

                            <div class="col-12 pt-3">
                                <h5 class="fw-bold border-bottom pb-2 text-dark">Steps Section Header</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Section Title</label>
                                <input type="text" name="section_title" class="form-control" value="{{ old('section_title', $settings->section_title) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Section Subtitle</label>
                                <input type="text" name="section_description" class="form-control" value="{{ old('section_description', $settings->section_description) }}">
                            </div>

                            <div class="col-12 pt-3">
                                <h5 class="fw-bold border-bottom pb-2 text-dark">FAQ Section Header</h5>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">FAQ Eyebrow</label>
                                <input type="text" name="faq_eyebrow" class="form-control" value="{{ old('faq_eyebrow', $settings->faq_eyebrow) }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">FAQ Title</label>
                                <input type="text" name="faq_title" class="form-control" value="{{ old('faq_title', $settings->faq_title) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">FAQ Highlight</label>
                                <input type="text" name="faq_highlight" class="form-control" value="{{ old('faq_highlight', $settings->faq_highlight) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">FAQ Description</label>
                                <textarea name="faq_description" class="form-control" rows="2">{{ old('faq_description', $settings->faq_description) }}</textarea>
                            </div>

                            <div class="col-12 pt-3">
                                <h5 class="fw-bold border-bottom pb-2 text-dark">SEO & Status</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SEO Meta Title</label>
                                <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $settings->seo_title) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">SEO Keywords</label>
                                <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $settings->seo_keywords) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Page Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ $settings->status === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $settings->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary fw-bold px-4">
                                    <i class="bi bi-save me-1"></i> Save Page Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TAB 3: APP CTA BANNER --}}
                <div class="tab-pane fade {{ $activeTab === 'cta' ? 'show active' : '' }}" id="ctaTab" role="tabpanel">
                    <form action="{{ route('admin.how-it-works.cta.update') }}" method="POST" enctype="multipart/form-data" class="mb-5">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold border-bottom pb-2 text-dark">App Download CTA Banner Settings</h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Eyebrow / Badge Text <span class="text-danger">*</span></label>
                                <input type="text" name="eyebrow" class="form-control" value="{{ old('eyebrow', $cta->eyebrow) }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Main Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $cta->title) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $cta->description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Google Play Store URL</label>
                                <input type="text" name="google_play_url" class="form-control" value="{{ old('google_play_url', $cta->google_play_url) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Apple App Store URL</label>
                                <input type="text" name="app_store_url" class="form-control" value="{{ old('app_store_url', $cta->app_store_url) }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Phone Mockup Image Path</label>
                                <input type="text" name="main_image" class="form-control" value="{{ old('main_image', $cta->main_image) }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Or Upload New Phone Mockup</label>
                                <input type="file" name="main_image_file" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ $cta->status === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $cta->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            @if($cta->main_image)
                                <div class="col-12">
                                    <div class="small text-muted mb-1">Current Phone Preview:</div>
                                    <img src="{{ asset($cta->main_image) }}" alt="Phone" class="rounded border p-1" style="max-height: 100px;">
                                </div>
                            @endif
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-success fw-bold px-4">
                                    <i class="bi bi-save me-1"></i> Update Banner Details
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex justify-content-between align-items-center mb-3 pt-3 border-top">
                        <div>
                            <h5 class="fw-bold mb-0">Banner Right-Side Features (4 Feature Points)</h5>
                            <p class="text-muted small mb-0">These features appear on the right side of the app mockup banner.</p>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createCtaFeatureModal">
                            <i class="bi bi-plus-lg me-1"></i> Add Feature Point
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">Icon</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th style="width: 80px;">Order</th>
                                    <th style="width: 90px;">Status</th>
                                    <th style="width: 120px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ctaFeatures as $feature)
                                    <tr>
                                        <td>
                                            <div class="rounded-circle bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i class="{{ $feature->icon }} fs-6"></i>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $feature->title }}</td>
                                        <td class="text-muted small">{{ $feature->description }}</td>
                                        <td>{{ $feature->sort_order }}</td>
                                        <td>
                                            <span class="badge {{ $feature->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $feature->status }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCtaFeatureModal{{ $feature->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.how-it-works.cta-features.destroy', $feature->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this feature point?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit CTA Feature Modal --}}
                                    <div class="modal fade" id="editCtaFeatureModal{{ $feature->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.how-it-works.cta-features.update', $feature->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title fw-bold">Edit Feature Point</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Feature Title <span class="text-danger">*</span></label>
                                                            <input type="text" name="title" class="form-control" value="{{ old('title', $feature->title) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control" rows="2">{{ old('description', $feature->description) }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Icon Class <span class="text-danger">*</span></label>
                                                            <input type="text" name="icon" class="form-control" value="{{ old('icon', $feature->icon) }}" required placeholder="e.g. bi-geo-alt-fill">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Sort Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $feature->sort_order) }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Active" {{ $feature->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $feature->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Update Feature</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No features defined. Click "Add Feature Point" above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 4: FAQS --}}
                <div class="tab-pane fade {{ $activeTab === 'faqs' ? 'show active' : '' }}" id="faqsTab" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Frequently Asked Questions</h5>
                        <button type="button" class="btn btn-dark btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                            <i class="bi bi-plus-lg me-1"></i> Add FAQ
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 120px;">Category</th>
                                    <th>Question</th>
                                    <th>Answer Preview</th>
                                    <th style="width: 80px;">Order</th>
                                    <th style="width: 90px;">Status</th>
                                    <th style="width: 120px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $faq->category ?? 'General' }}</span>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $faq->question }}</td>
                                        <td class="text-muted small text-truncate" style="max-width: 350px;">{{ $faq->answer }}</td>
                                        <td>{{ $faq->sort_order }}</td>
                                        <td>
                                            <span class="badge {{ $faq->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $faq->status }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.how-it-works.faqs.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Edit FAQ Modal --}}
                                    <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.how-it-works.faqs.update', $faq->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title fw-bold">Edit FAQ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="row g-3">
                                                            <div class="col-md-8">
                                                                <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                                                                <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-semibold">Category</label>
                                                                <input type="text" name="category" class="form-control" value="{{ old('category', $faq->category) }}" placeholder="e.g. Booking, Payment">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
                                                                <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Sort Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order) }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Active" {{ $faq->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $faq->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Update FAQ</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No FAQs configured yet. Click "Add FAQ" above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal: Create Step --}}
<div class="modal fade" id="createStepModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.how-it-works.steps.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-warning me-2"></i>Add New Step</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Step Number <span class="text-danger">*</span></label>
                            <input type="text" name="step_number" class="form-control" value="{{ sprintf('%02d', count($steps) + 1) }}" required placeholder="e.g. 05">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. Scan & Enter">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ count($steps) + 1 }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Provide a concise 1-2 sentence description..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bootstrap Icon Class</label>
                            <input type="text" name="icon" class="form-control" value="bi-check-circle" placeholder="e.g. bi-search, bi-calendar3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Image Path</label>
                            <input type="text" name="image" class="form-control" placeholder="images/how-it-works/step-01.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Or Upload Image File</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Create Step</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Create CTA Feature --}}
<div class="modal fade" id="createCtaFeatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.how-it-works.cta-features.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-success me-2"></i>Add App Feature Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Feature Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. 24/7 Security">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief feature explanation..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon Class <span class="text-danger">*</span></label>
                        <input type="text" name="icon" class="form-control" value="bi-shield-check" required placeholder="e.g. bi-geo-alt-fill">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ count($ctaFeatures) + 1 }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold">Create Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Create FAQ --}}
<div class="modal fade" id="createFaqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.how-it-works.faqs.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="bi bi-question-circle text-info me-2"></i>Add New FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" class="form-control" required placeholder="e.g. How do I get my parking pass?">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="Booking" placeholder="e.g. Booking, Payment, General">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" class="form-control" rows="4" required placeholder="Write the answer clearly for customers..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ count($faqs) + 1 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">Create FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
