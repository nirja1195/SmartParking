@extends('layouts.admin')

@section('title', 'Location Details - ' . $location->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small text-muted mb-1">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted">Parking Locations</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $location->name }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $location->image_url }}" alt="{{ $location->name }}" class="rounded-3 border" style="width: 80px; height: 55px; object-fit: cover;">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="fw-extrabold text-dark mb-0" style="font-size: 1.8rem;">{{ $location->name }}</h2>
                    <span class="{{ $location->type_badge_class }}">{{ $location->location_type ?: 'Commercial' }}</span>
                    <span class="{{ $location->status === 'Active' ? 'badge-status-active' : 'badge-status-inactive' }}">{{ $location->status }}</span>
                </div>
                <div class="text-muted small mt-1">
                    <i class="bi bi-geo-alt text-secondary me-1"></i> {{ $location->address }}, {{ $location->city }}, {{ $location->state }}
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.locations.edit', $location->id) }}" class="btn btn-warning text-dark fw-bold btn-sm rounded-2">
                <i class="bi bi-pencil me-1"></i> Edit Facility
            </a>
            <a href="{{ route('admin.locations.index') }}" class="btn btn-outline-secondary btn-sm rounded-2">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Location Slot Breakdown Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center">
                <div class="text-muted small fw-medium">Total Slots</div>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($slotStats['total']) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center" style="border-left: 4px solid #10B981 !important;">
                <div class="text-success small fw-medium">Available</div>
                <h4 class="fw-bold text-success mb-0">{{ number_format($slotStats['available']) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center" style="border-left: 4px solid #3B82F6 !important;">
                <div class="text-primary small fw-medium">Reserved</div>
                <h4 class="fw-bold text-primary mb-0">{{ number_format($slotStats['reserved']) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center" style="border-left: 4px solid #EF4444 !important;">
                <div class="text-danger small fw-medium">Occupied</div>
                <h4 class="fw-bold text-danger mb-0">{{ number_format($slotStats['occupied']) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center" style="border-left: 4px solid #6B7280 !important;">
                <div class="text-secondary small fw-medium">Maintenance</div>
                <h4 class="fw-bold text-secondary mb-0">{{ number_format($slotStats['maintenance']) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-custom p-3 text-center" style="border-left: 4px solid #1F2937 !important;">
                <div class="text-dark small fw-medium">Blocked</div>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($slotStats['blocked']) }}</h4>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4 mb-4">
        <!-- Overview & Specs -->
        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-dark mb-3">Facility Details & Operating Rules</h5>
                <table class="table table-borderless table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-secondary fw-medium" style="width: 150px;">Location Type:</td>
                            <td class="fw-bold text-dark">{{ $location->location_type ?: 'Commercial' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary fw-medium">Operating Hours:</td>
                            <td class="fw-bold text-dark">{{ $location->opening_time }} – {{ $location->closing_time }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary fw-medium">Hourly Tariff:</td>
                            <td class="fw-bold text-success">₹ {{ number_format($location->parking_charges, 2) }} / hr</td>
                        </tr>
                        <tr>
                            <td class="text-secondary fw-medium">Contact Phone:</td>
                            <td class="text-dark">{{ $location->contact_number ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary fw-medium">GPS Coordinates:</td>
                            <td class="text-dark">{{ $location->latitude ?: '-' }}, {{ $location->longitude ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary fw-medium">Description:</td>
                            <td class="text-dark small">{{ $location->description ?: 'No detailed description provided.' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Zones & Capacity Overview -->
        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Associated Zones ({{ $location->zones->count() }})</h5>
                    <a href="{{ route('admin.parking-zones.create', ['location_id' => $location->id]) }}" class="btn btn-sm btn-outline-primary">
                        + Add Zone
                    </a>
                </div>
                
                <div class="list-group list-group-flush">
                    @forelse($location->zones as $zone)
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-dark">{{ $zone->zone_name }}</div>
                                <small class="text-muted">{{ $zone->vehicle_type }} &bull; {{ $zone->floor }} Floor &bull; Section {{ $zone->section }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $zone->slots->count() }} Slots</span>
                        </div>
                    @empty
                        <div class="py-3 text-muted text-center small">No zones added yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
