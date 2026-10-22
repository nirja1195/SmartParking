@extends('layouts.admin')

@section('title', 'Manage Parking Services & Services Page CMS')
@section('page-title', 'Services Management & CMS')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-1">Services Management & CMS</h1>
            <p class="text-muted small mb-0"><span class="badge bg-warning bg-opacity-25 text-dark me-1">Dynamic Parking Services</span> Control all public parking services, custom solution banners, hero typography, and 4-item feature strip.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('services.index') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Public Services Page
            </a>
            <button type="button" class="btn btn-warning fw-bold text-dark btn-sm" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                <i class="bi bi-plus-circle me-1"></i> Add New Service
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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-bottom pt-3 pb-0 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="servicesCmsTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold text-dark" id="services-list-tab" data-bs-toggle="tab" data-bs-target="#servicesList" type="button" role="tab">
                        <i class="bi bi-grid-3x3-gap-fill me-1 text-warning"></i> 1. Parking Services ({{ count($services) }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="page-cms-tab" data-bs-toggle="tab" data-bs-target="#pageCms" type="button" role="tab">
                        <i class="bi bi-layout-text-window-reverse me-1 text-primary"></i> 2. Page Hero & Banner CMS
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold text-dark" id="features-strip-tab" data-bs-toggle="tab" data-bs-target="#featuresStrip" type="button" role="tab">
                        <i class="bi bi-shield-check me-1 text-success"></i> 3. Feature Strip ({{ count($features) }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 bg-light">
            <div class="tab-content" id="servicesCmsContent">
                
                <!-- TAB 1: PARKING SERVICES LIST -->
                <div class="tab-pane fade show active" id="servicesList" role="tabpanel">
                    <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 70px;">Order</th>
                                        <th style="width: 140px;">Image / Icon</th>
                                        <th>Service Details</th>
                                        <th>Slug & Action</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary rounded-pill px-2.5 py-1.5">{{ $service->sort_order }}</span>
                                        </td>
                                        <td>
                                            <div class="position-relative d-inline-block" style="width: 100px; height: 55px;">
                                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="rounded-3 border object-fit-cover w-100 h-100">
                                                <div class="position-absolute bottom-0 start-0 translate-middle-y ms-1 rounded-circle bg-warning d-flex align-items-center justify-content-center text-dark" style="width: 26px; height: 26px; font-size: 0.8rem; border: 2px solid #fff;">
                                                    <i class="{{ $service->icon ?: 'bi bi-car-front-fill' }}"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $service->name }}</div>
                                            <p class="text-secondary small mb-0 text-truncate" style="max-width: 280px;">{{ $service->description }}</p>
                                        </td>
                                        <td>
                                            <code class="small text-muted">/services/{{ $service->slug }}</code>
                                            @if($service->button_url)
                                                <div class="small text-primary"><i class="bi bi-link-45deg"></i> Custom URL: {{ $service->button_url }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.services.toggle-status', $service->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $service->status === 'Active' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3 py-1">
                                                    <i class="bi {{ $service->status === 'Active' ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                                    {{ $service->status }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="View Public Page">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $service->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete service {{ $service->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Service: {{ $service->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Service Name</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Slug (URL)</label>
                                                                <input type="text" name="slug" class="form-control" value="{{ $service->slug }}">
                                                                <div class="form-text">Leave blank to auto-generate from name.</div>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label fw-bold">Short Description (Card Subtitle)</label>
                                                                <input type="text" name="short_description" class="form-control" value="{{ $service->short_description ?: $service->description }}">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label fw-bold">Full Description</label>
                                                                <textarea name="description" class="form-control" rows="3">{{ $service->description }}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Bootstrap Icon Class</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="{{ $service->icon ?: 'bi bi-car-front-fill' }}"></i></span>
                                                                    <input type="text" name="icon" class="form-control" value="{{ $service->icon }}" placeholder="bi bi-car-front-fill">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-bold">Sort Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ $service->sort_order }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-bold">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Active" {{ $service->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $service->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Current Image</label>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <img src="{{ asset($service->image) }}" alt="Preview" class="rounded border" style="width: 80px; height: 50px; object-fit: cover;">
                                                                    <input type="text" name="image" class="form-control form-control-sm" value="{{ $service->image }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Upload New Image (Replaces Current)</label>
                                                                <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                                                                <div class="form-text">Formats: JPG, PNG, WEBP (Max 4MB).</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Button Text</label>
                                                                <input type="text" name="button_text" class="form-control" value="{{ $service->button_text ?: 'Book Now' }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Button URL (Optional)</label>
                                                                <input type="text" name="button_url" class="form-control" value="{{ $service->button_url }}" placeholder="Defaults to /services/{{ $service->slug }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No parking services configured yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PAGE HERO, BANNER & SEO CMS -->
                <div class="tab-pane fade" id="pageCms" role="tabpanel">
                    <form action="{{ route('admin.services.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Hero Section -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-image text-warning me-2"></i>Services Hero Section</h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Eyebrow Badge Text</label>
                                    <input type="text" name="hero_eyebrow" class="form-control" value="{{ $settings->hero_eyebrow }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Main Heading Line 1</label>
                                    <input type="text" name="hero_title" class="form-control" value="{{ $settings->hero_title }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold text-muted">Highlighted Heading (Yellow Accent)</label>
                                    <input type="text" name="hero_highlight" class="form-control" value="{{ $settings->hero_highlight }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Description Subtext</label>
                                    <textarea name="hero_description" class="form-control" rows="2">{{ $settings->hero_description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Hero Background Asset Path</label>
                                    <input type="text" name="hero_image" class="form-control" value="{{ $settings->hero_image }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Upload New Hero Image Layer</label>
                                    <input type="file" name="hero_image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                                </div>
                            </div>
                        </div>

                        <!-- Catalog Headers & Search -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid text-primary me-2"></i>Services Grid Header & Search</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Section Title</label>
                                    <input type="text" name="services_section_title" class="form-control" value="{{ $settings->services_section_title }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Search Placeholder</label>
                                    <input type="text" name="search_placeholder" class="form-control" value="{{ $settings->search_placeholder }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Section Description</label>
                                    <input type="text" name="services_section_description" class="form-control" value="{{ $settings->services_section_description }}">
                                </div>
                            </div>
                        </div>

                        <!-- Custom Solution Banner -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-megaphone text-warning me-2"></i>Custom Solution Banner</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Banner Icon (Bootstrap Icon)</label>
                                    <input type="text" name="custom_banner_icon" class="form-control" value="{{ $settings->custom_banner_icon }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Heading Title</label>
                                    <input type="text" name="custom_banner_title" class="form-control" value="{{ $settings->custom_banner_title }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Button Text</label>
                                    <input type="text" name="custom_banner_button_text" class="form-control" value="{{ $settings->custom_banner_button_text }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-muted">Description</label>
                                    <input type="text" name="custom_banner_description" class="form-control" value="{{ $settings->custom_banner_description }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Button URL</label>
                                    <input type="text" name="custom_banner_button_url" class="form-control" value="{{ $settings->custom_banner_button_url }}">
                                </div>
                            </div>
                        </div>

                        <!-- SEO Metadata -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-search text-info me-2"></i>Page SEO Configuration</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Page Title (&lt;title&gt;)</label>
                                    <input type="text" name="seo_title" class="form-control" value="{{ $settings->seo_title }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Meta Description</label>
                                    <input type="text" name="seo_description" class="form-control" value="{{ $settings->seo_description }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                <i class="bi bi-check-lg me-1"></i> Save Services Page Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 3: FEATURE STRIP CRUD -->
                <div class="tab-pane fade" id="featuresStrip" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Feature Strip Items (Displayed below the custom banner)</h6>
                        <button type="button" class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createFeatureModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Feature Item
                        </button>
                    </div>

                    <div class="bg-white rounded-4 shadow-sm border overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 70px;">Order</th>
                                        <th style="width: 80px;">Icon</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($features as $feat)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary rounded-pill">{{ $feat->sort_order }}</span>
                                        </td>
                                        <td>
                                            <div class="rounded-circle bg-warning bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i class="{{ $feat->icon }} text-dark fs-5"></i>
                                            </div>
                                        </td>
                                        <td><span class="fw-bold text-dark">{{ $feat->title }}</span></td>
                                        <td><span class="text-secondary small">{{ $feat->description }}</span></td>
                                        <td>
                                            <span class="badge {{ $feat->status === 'Active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-2.5 py-1">
                                                {{ $feat->status }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editFeatModal{{ $feat->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('admin.services.features.destroy', $feat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this feature?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Feature Modal -->
                                    <div class="modal fade" id="editFeatModal{{ $feat->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.services.features.update', $feat->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Feature Strip Item</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Feature Title</label>
                                                            <input type="text" name="title" class="form-control" value="{{ $feat->title }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Description Subtext</label>
                                                            <input type="text" name="description" class="form-control" value="{{ $feat->description }}" required>
                                                        </div>
                                                        <div class="row g-2 mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-bold">Bootstrap Icon Class</label>
                                                                <input type="text" name="icon" class="form-control" value="{{ $feat->icon }}" required>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-bold">Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ $feat->sort_order }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-bold">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="Active" {{ $feat->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $feat->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No feature strip items defined.</td>
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
</div>

<!-- Create Service Modal -->
<div class="modal fade" id="createServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Parking Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Service Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. VIP Valet Parking" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Slug (Optional)</label>
                            <input type="text" name="slug" class="form-control" placeholder="auto-generated-if-empty">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Short Description (Card Subtitle)</label>
                            <input type="text" name="short_description" class="form-control" placeholder="e.g. Premium valet service at your convenience.">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Full Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Detailed service description..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bootstrap Icon Class</label>
                            <input type="text" name="icon" class="form-control" value="bi bi-car-front-fill" placeholder="bi bi-car-front-fill">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ count($services) + 1 }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Upload Image</label>
                            <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Or Asset Path</label>
                            <input type="text" name="image" class="form-control" value="images/services/card-car.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Button Text</label>
                            <input type="text" name="button_text" class="form-control" value="Book Now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Button URL (Optional)</label>
                            <input type="text" name="button_url" class="form-control" placeholder="Leave empty for /services/{slug}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Create Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Feature Modal -->
<div class="modal fade" id="createFeatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.services.features.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Feature Strip Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Feature Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Safe & Secure" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description Subtext</label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. 24/7 surveillance" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Bootstrap Icon Class</label>
                            <input type="text" name="icon" class="form-control" value="bi bi-shield-check" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ count($features) + 1 }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold">Add Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
