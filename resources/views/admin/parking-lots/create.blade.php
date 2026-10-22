@extends('layouts.admin')

@section('title', 'Add Parking Facility')
@section('page-title', 'Add New Parking Facility')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Facility Information</h5>

            <form method="POST" action="{{ route('admin.parking-lots.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label fw-semibold">Facility Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Grand Central Garage">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="code" class="form-label fw-semibold">Facility Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g. GCG-01" style="text-transform: uppercase;">
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="address" class="form-label fw-semibold">Street Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required placeholder="123 Commerce Way">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="city" class="form-label fw-semibold">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required placeholder="Metropolis">
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="total_spots" class="form-label fw-semibold">Total Spot Capacity</label>
                        <input type="number" class="form-control @error('total_spots') is-invalid @enderror" id="total_spots" name="total_spots" value="{{ old('total_spots', 30) }}" min="1" max="2000" required>
                        <small class="text-muted">Individual spot numbers will be automatically generated.</small>
                        @error('total_spots') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="hourly_rate" class="form-label fw-semibold">Base Hourly Rate ($)</label>
                        <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', 25.00) }}" required>
                        @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Floors / Levels</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="floors[]" value="Ground" id="floorG" checked>
                            <label class="form-check-label" for="floorG">Ground Floor</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="floors[]" value="Level 1" id="floor1" checked>
                            <label class="form-check-label" for="floor1">Level 1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="floors[]" value="Level 2" id="floor2">
                            <label class="form-check-label" for="floor2">Level 2</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.parking-lots.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Facility</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
