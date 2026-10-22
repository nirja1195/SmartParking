@extends('layouts.admin')

@section('title', 'Edit Parking Zone')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Edit Zone: {{ $zone->zone_name }}</h3>
        <p class="text-muted mb-0">Update parking location association and vehicle constraints</p>
    </div>
    <a href="{{ route('admin.parking-zones.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.parking-zones.update', $zone->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Select Parking Facility <span class="text-danger">*</span></label>
                    <select name="parking_location_id" class="form-select" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ (old('parking_location_id', $zone->parking_location_id) == $loc->id) ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Zone Name <span class="text-danger">*</span></label>
                    <input type="text" name="zone_name" class="form-control" value="{{ old('zone_name', $zone->zone_name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Zone Short Code</label>
                    <input type="text" name="zone_code" class="form-control text-uppercase" placeholder="e.g. ZA, ZB" value="{{ old('zone_code', $zone->zone_code) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Designated Vehicle Type <span class="text-danger">*</span></label>
                    <select name="vehicle_type" class="form-select" required>
                        @foreach(['Car', 'Bike', 'EV', 'Auto', 'Bus', 'Truck'] as $type)
                            <option value="{{ $type }}" {{ old('vehicle_type', $zone->vehicle_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Floor / Level <span class="text-danger">*</span></label>
                    <input type="text" name="floor" class="form-control" value="{{ old('floor', $zone->floor) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Section / Wing</label>
                    <input type="text" name="section" class="form-control" value="{{ old('section', $zone->section) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Active" {{ old('status', $zone->status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $zone->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Zone Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $zone->description) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Update Zone
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
