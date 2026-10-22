@extends('layouts.user')

@section('title', 'Find Parking')

@section('content')
<div class="card p-4 mb-4">
    <h4 class="fw-bold mb-3 text-dark">Search Nearby Parking</h4>

    <!-- Search Form -->
    <form method="GET" action="{{ route('user.find-parking') }}" class="row g-3">
        <div class="col-md-3">
            <label for="city" class="form-label small fw-semibold text-muted">City</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                <input type="text" class="form-control" id="city" name="city" placeholder="e.g. Metropolis" value="{{ $filters['city'] ?? '' }}">
            </div>
        </div>

        <div class="col-md-3">
            <label for="location" class="form-label small fw-semibold text-muted">Location / Mall Name</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-building"></i></span>
                <input type="text" class="form-control" id="location" name="location" placeholder="e.g. City Center Mall" value="{{ $filters['location'] ?? '' }}">
            </div>
        </div>

        <div class="col-md-2">
            <label for="vehicle_type" class="form-label small fw-semibold text-muted">Vehicle Type</label>
            <select class="form-select" id="vehicle_type" name="vehicle_type">
                <option value="">All Types</option>
                <option value="Car" {{ ($filters['vehicle_type'] ?? '') == 'Car' ? 'selected' : '' }}>Car</option>
                <option value="Bike" {{ ($filters['vehicle_type'] ?? '') == 'Bike' ? 'selected' : '' }}>Bike</option>
                <option value="EV" {{ ($filters['vehicle_type'] ?? '') == 'EV' ? 'selected' : '' }}>EV (Electric)</option>
                <option value="Auto" {{ ($filters['vehicle_type'] ?? '') == 'Auto' ? 'selected' : '' }}>Auto</option>
                <option value="Truck" {{ ($filters['vehicle_type'] ?? '') == 'Truck' ? 'selected' : '' }}>Truck</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="date" class="form-label small fw-semibold text-muted">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $filters['date'] ?? date('Y-m-d') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                <i class="bi bi-search me-1"></i> Search
            </button>
            <a href="{{ route('user.find-parking') }}" class="btn btn-outline-secondary" title="Reset Filters">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- Parking Facility Cards List -->
<div class="row g-4">
    @forelse($locations as $location)
        <div class="col-lg-6">
            <div class="card h-100 p-4 border shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $location->name }}</h4>
                        <span class="text-muted small"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $location->address }}, {{ $location->city }}</span>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">
                        ${{ number_format($location->parking_charges, 2) }} / hr
                    </span>
                </div>

                <p class="text-muted small mt-2 mb-3">
                    {{ $location->description ?? 'Secure parking facility with 24/7 access.' }}
                </p>

                <!-- Facilities Badges -->
                @if($location->facilities)
                    <div class="mb-3 d-flex flex-wrap gap-1">
                        @foreach(explode(',', $location->facilities) as $fac)
                            <span class="badge bg-light text-dark border">{{ trim($fac) }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="p-3 bg-light rounded mb-3">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <small class="text-muted d-block">Available Slots</small>
                            <span class="fs-4 fw-bold text-success">{{ $location->availableSlotsCount() }}</span>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Total Capacity</small>
                            <span class="fs-4 fw-bold text-dark">{{ $location->total_capacity }}</span>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Hours</small>
                            <span class="small fw-semibold text-secondary d-block mt-1">
                                {{ date('h:i A', strtotime($location->opening_time)) }} - {{ date('h:i A', strtotime($location->closing_time)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                    <span class="badge bg-primary-subtle text-primary">Status: {{ $location->status }}</span>
                    <a href="{{ route('user.parking.show', $location->id) }}" class="btn btn-primary px-4 fw-semibold">
                        VIEW PARKING <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center">
            <i class="bi bi-building-slash fs-1 text-muted"></i>
            <h5 class="fw-bold mt-2 text-dark">No Parking Locations Found</h5>
            <p class="text-muted small">No active parking facilities matched your search parameters. Try changing the city or vehicle filter.</p>
            <a href="{{ route('user.find-parking') }}" class="btn btn-primary btn-sm mt-2">Reset Filters</a>
        </div>
    @endforelse
</div>
@endsection
