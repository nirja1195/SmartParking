@extends('layouts.customer')

@section('title', 'My Registered Vehicles')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Registered Vehicles</h5>
            <div class="list-group list-group-flush">
                @forelse($vehicles as $vehicle)
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-3 rounded-circle me-3 fs-4 text-primary">
                                <i class="bi bi-car-front-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $vehicle->make }} {{ $vehicle->model }}</h6>
                                <div class="text-muted small">
                                    <span class="font-monospace fw-bold text-dark">{{ $vehicle->license_plate }}</span> &bull;
                                    Color: {{ $vehicle->color ?? 'Standard' }} &bull;
                                    Type: <span class="text-uppercase">{{ $vehicle->vehicle_type }}</span>
                                </div>
                                <small class="text-muted">{{ $vehicle->bookings_count }} bookings linked</small>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('customer.vehicles.destroy', $vehicle->id) }}" onsubmit="return confirm('Remove this vehicle?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted py-3 mb-0">No vehicles registered yet. Add your vehicle to speed up check-in!</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Add New Vehicle</h5>
            <form method="POST" action="{{ route('customer.vehicles.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="license_plate" class="form-label fw-semibold">License Plate #</label>
                    <input type="text" class="form-control text-uppercase @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required placeholder="e.g. ABC-1234">
                    @error('license_plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="make" class="form-label fw-semibold">Make</label>
                        <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" value="{{ old('make') }}" required placeholder="e.g. Honda">
                    </div>
                    <div class="col-6">
                        <label for="model" class="form-label fw-semibold">Model</label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}" required placeholder="e.g. Civic">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="color" class="form-label fw-semibold">Color</label>
                        <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" placeholder="e.g. Silver">
                    </div>
                    <div class="col-6">
                        <label for="vehicle_type" class="form-label fw-semibold">Vehicle Type</label>
                        <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                            <option value="standard">Standard Sedan / SUV</option>
                            <option value="compact">Compact / 2-Wheeler</option>
                            <option value="ev">Electric Vehicle (EV)</option>
                            <option value="truck">Truck / Van</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Save Vehicle</button>
            </form>
        </div>
    </div>
</div>
@endsection
