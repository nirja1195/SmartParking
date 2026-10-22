@extends('layouts.user')

@section('title', 'My Vehicles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">My Vehicles</h3>
        <p class="text-muted mb-0">Manage registered vehicles for fast one-click parking reservations</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        <i class="bi bi-plus-circle me-1"></i> Register New Vehicle
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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

<div class="row g-4">
    @forelse($vehicles as $vehicle)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm {{ $vehicle->is_default ? 'border-primary border-2' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 text-primary me-3 fs-4">
                                @if(in_array($vehicle->vehicle_type, ['Bike', 'Motorcycle']))
                                    <i class="bi bi-bicycle"></i>
                                @elseif($vehicle->vehicle_type == 'EV')
                                    <i class="bi bi-ev-front-fill"></i>
                                @elseif(in_array($vehicle->vehicle_type, ['Bus', 'Truck']))
                                    <i class="bi bi-truck"></i>
                                @else
                                    <i class="bi bi-car-front-fill"></i>
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-secondary mb-1">{{ $vehicle->vehicle_type }}</span>
                                <h5 class="fw-bold font-monospace mb-0">{{ $vehicle->vehicle_number }}</h5>
                            </div>
                        </div>
                        @if($vehicle->is_default)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1">Default</span>
                        @endif
                    </div>

                    <div class="text-muted small mb-3">
                        <div><strong>Model:</strong> {{ $vehicle->vehicle_model ?? 'Not specified' }}</div>
                        <div><strong>Color:</strong> {{ $vehicle->vehicle_color ?? 'Not specified' }}</div>
                        <div><strong>Total Bookings:</strong> {{ $vehicle->bookings_count }}</div>
                    </div>

                    <hr class="opacity-10 my-3">

                    <div class="d-flex justify-content-between align-items-center">
                        @if(!$vehicle->is_default)
                            <form action="{{ route('user.vehicles.default', $vehicle->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-star me-1"></i> Make Default
                                </button>
                            </form>
                        @else
                            <span class="text-success small"><i class="bi bi-check2-all me-1"></i> Primary Vehicle</span>
                        @endif

                        <form action="{{ route('user.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this vehicle?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm py-5 text-center">
                <i class="bi bi-car-front display-4 text-muted mb-3"></i>
                <h5 class="fw-bold">No Vehicles Registered</h5>
                <p class="text-muted">Add your primary vehicle to speed up parking slot booking.</p>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Vehicle Now
                    </button>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal: Add Vehicle -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('user.vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-car-front-fill me-2 text-primary"></i> Register New Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Vehicle License Plate Number <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_number" class="form-control text-uppercase" placeholder="e.g. MH-12-AB-1234 / DL-01-XY-9999" required value="{{ old('vehicle_number') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Vehicle Type <span class="text-danger">*</span></label>
                        <select name="vehicle_type" class="form-select" required>
                            <option value="Car" {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>Car (Sedan / SUV / Hatchback)</option>
                            <option value="Bike" {{ old('vehicle_type') == 'Bike' ? 'selected' : '' }}>Two-Wheeler / Bike</option>
                            <option value="EV" {{ old('vehicle_type') == 'EV' ? 'selected' : '' }}>Electric Vehicle (EV)</option>
                            <option value="Auto" {{ old('vehicle_type') == 'Auto' ? 'selected' : '' }}>Auto Rickshaw</option>
                            <option value="Bus" {{ old('vehicle_type') == 'Bus' ? 'selected' : '' }}>Bus</option>
                            <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck / Heavy</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Model / Make</label>
                            <input type="text" name="vehicle_model" class="form-control" placeholder="e.g. Honda City" value="{{ old('vehicle_model') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Color</label>
                            <input type="text" name="vehicle_color" class="form-control" placeholder="e.g. Silver / Black" value="{{ old('vehicle_color') }}">
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" {{ $vehicles->isEmpty() ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">
                            Set as primary default vehicle
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
