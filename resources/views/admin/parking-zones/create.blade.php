@extends('layouts.admin')

@section('title', 'Add Parking Zone')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Create Parking Zone</h3>
        <p class="text-muted mb-0">Define a sector, designated vehicle type, and level for parking bays</p>
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
        <form action="{{ route('admin.parking-zones.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Select Parking Facility <span class="text-danger">*</span></label>
                    <select name="parking_location_id" class="form-select" required>
                        <option value="">-- Choose Location --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ (old('parking_location_id', $selectedLocationId) == $loc->id) ? 'selected' : '' }}>
                                {{ $loc->name }} ({{ $loc->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Zone Name <span class="text-danger">*</span></label>
                    <input type="text" name="zone_name" class="form-control" placeholder="e.g. Zone A - Premium North" value="{{ old('zone_name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Zone Short Code</label>
                    <input type="text" name="zone_code" class="form-control text-uppercase" placeholder="e.g. ZA, ZB, N1" value="{{ old('zone_code') }}">
                    <small class="text-muted">Short identifier used for signage and prefixing</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Designated Vehicle Type <span class="text-danger">*</span></label>
                    <select name="vehicle_type" class="form-select" required>
                        <option value="Car" {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>Car</option>
                        <option value="Bike" {{ old('vehicle_type') == 'Bike' ? 'selected' : '' }}>Two-Wheeler / Bike</option>
                        <option value="EV" {{ old('vehicle_type') == 'EV' ? 'selected' : '' }}>EV (Electric Vehicle Charging Bay)</option>
                        <option value="Auto" {{ old('vehicle_type') == 'Auto' ? 'selected' : '' }}>Auto Rickshaw</option>
                        <option value="Bus" {{ old('vehicle_type') == 'Bus' ? 'selected' : '' }}>Bus</option>
                        <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck / Heavy</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Floor / Level <span class="text-danger">*</span></label>
                    <input type="text" name="floor" class="form-control" placeholder="e.g. Ground Floor, 1st Floor, Basement 2" value="{{ old('floor', 'Ground Floor') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Section / Wing</label>
                    <input type="text" name="section" class="form-control" placeholder="e.g. East Wing, Sector 3" value="{{ old('section') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', 'Inactive') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Zone Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="e.g. Near main elevator, covered zone, high clearance">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i> Save Zone & Add Slots
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
