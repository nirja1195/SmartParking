@extends('layouts.admin')

@section('title', 'Add New Location')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small text-muted mb-1">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted">Parking Locations</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Add Location</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-extrabold text-dark mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">
                Add New <span class="text-yellow">Location</span>
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Define new parking facility details, location type, image, operating hours and pricing.</p>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm rounded-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Locations
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the form errors below.
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-custom p-4">
        <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Location Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. City Center Mall" value="{{ old('name') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Location Type <span class="text-danger">*</span></label>
                    <select name="location_type" class="form-select" required style="border-radius: 8px;">
                        <option value="Commercial" {{ old('location_type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="Airport" {{ old('location_type') === 'Airport' ? 'selected' : '' }}>Airport</option>
                        <option value="Office" {{ old('location_type') === 'Office' ? 'selected' : '' }}>Office</option>
                        <option value="Transit" {{ old('location_type') === 'Transit' ? 'selected' : '' }}>Transit</option>
                        <option value="Hospital" {{ old('location_type') === 'Hospital' ? 'selected' : '' }}>Hospital</option>
                        <option value="Educational" {{ old('location_type') === 'Educational' ? 'selected' : '' }}>Educational</option>
                        <option value="Public" {{ old('location_type') === 'Public' ? 'selected' : '' }}>Public</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Street Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" placeholder="e.g. CG Road, Ahmedabad" value="{{ old('address') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Area / Locality</label>
                    <input type="text" name="area" class="form-control" placeholder="e.g. CG Road / Navrangpura" value="{{ old('area') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control" placeholder="e.g. Ahmedabad" value="{{ old('city', 'Ahmedabad') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">State</label>
                    <input type="text" name="state" class="form-control" placeholder="e.g. Gujarat" value="{{ old('state', 'Gujarat') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Postal Code</label>
                    <input type="text" name="pincode" class="form-control" placeholder="e.g. 380009" value="{{ old('pincode') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Opening Time <span class="text-danger">*</span></label>
                    <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', '08:00') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Closing Time <span class="text-danger">*</span></label>
                    <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', '23:00') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Base Hourly Rate (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="1" min="0" name="parking_charges" class="form-control" placeholder="e.g. 30" value="{{ old('parking_charges', '30') }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" placeholder="e.g. +91 79 2640 0001" value="{{ old('contact_number') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required style="border-radius: 8px;">
                        <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active (Available for customer booking)</option>
                        <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive (Offline / Under setup)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Latitude (GPS)</label>
                    <input type="number" step="0.0000001" name="latitude" class="form-control" placeholder="e.g. 23.0225" value="{{ old('latitude') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Longitude (GPS)</label>
                    <input type="number" step="0.0000001" name="longitude" class="form-control" placeholder="e.g. 72.5714" value="{{ old('longitude') }}" style="border-radius: 8px;">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Location Thumbnail Image</label>
                    <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp" style="border-radius: 8px;">
                    <small class="text-muted">Supported formats: JPG, PNG, WEBP (Max 4MB).</small>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Location Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Provide facility guidelines, entrance info or notes for drivers..." style="border-radius: 8px;">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2 border-0 shadow-sm rounded-3">
                    <i class="bi bi-check-circle me-1"></i> Save & Create Location
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
