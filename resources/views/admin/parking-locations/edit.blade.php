@extends('layouts.admin')

@section('title', 'Edit Location - ' . $location->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small text-muted mb-1">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted">Parking Locations</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Location</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-extrabold text-dark mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">
                Edit <span class="text-yellow">{{ $location->name }}</span>
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Update facility details, type, pricing, operating schedule, and photo.</p>
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
        <form action="{{ route('admin.locations.update', $location->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Location Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $location->name) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Location Type <span class="text-danger">*</span></label>
                    <select name="location_type" class="form-select" required style="border-radius: 8px;">
                        @foreach(['Commercial', 'Airport', 'Office', 'Transit', 'Hospital', 'Educational', 'Public'] as $type)
                            <option value="{{ $type }}" {{ old('location_type', $location->location_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Street Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $location->address) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Area / Locality</label>
                    <input type="text" name="area" class="form-control" value="{{ old('area', $location->area) }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $location->city) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $location->state) }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Postal Code</label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $location->pincode) }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Opening Time <span class="text-danger">*</span></label>
                    <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', $location->opening_time) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Closing Time <span class="text-danger">*</span></label>
                    <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', $location->closing_time) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-dark">Base Hourly Rate (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="1" min="0" name="parking_charges" class="form-control" value="{{ old('parking_charges', $location->parking_charges) }}" required style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $location->contact_number) }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required style="border-radius: 8px;">
                        <option value="Active" {{ old('status', $location->status) === 'Active' ? 'selected' : '' }}>Active (Available for customer booking)</option>
                        <option value="Inactive" {{ old('status', $location->status) === 'Inactive' ? 'selected' : '' }}>Inactive (Offline / Under maintenance)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Latitude (GPS)</label>
                    <input type="number" step="0.0000001" name="latitude" class="form-control" value="{{ old('latitude', $location->latitude) }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Longitude (GPS)</label>
                    <input type="number" step="0.0000001" name="longitude" class="form-control" value="{{ old('longitude', $location->longitude) }}" style="border-radius: 8px;">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Current Image</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ $location->image_url }}" alt="Current Image" class="rounded-2 border" style="width: 100px; height: 60px; object-fit: cover;">
                        <span class="text-secondary small">{{ $location->image ?: 'Using default image' }}</span>
                    </div>
                    <label class="form-label fw-bold small text-dark">Replace Image (Optional)</label>
                    <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp" style="border-radius: 8px;">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Location Description</label>
                    <textarea name="description" class="form-control" rows="3" style="border-radius: 8px;">{{ old('description', $location->description) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2 border-0 shadow-sm rounded-3">
                    <i class="bi bi-check-circle me-1"></i> Update Location
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
