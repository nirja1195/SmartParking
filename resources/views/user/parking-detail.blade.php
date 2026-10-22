@extends('layouts.user')

@section('title', $location->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('user.find-parking') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Search
    </a>

    <!-- Location Hero Card -->
    <div class="card p-4 shadow-sm border mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h3 class="fw-bold mb-1 text-dark">{{ $location->name }}</h3>
                <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $location->address }}, {{ $location->city }} ({{ $location->pincode }})</p>
            </div>
            <div class="text-end">
                <span class="fs-4 fw-bold text-success">${{ number_format($location->parking_charges, 2) }}</span>
                <small class="text-muted d-block">Base Rate per Hour</small>
            </div>
        </div>

        <p class="text-secondary small mb-3">{{ $location->description }}</p>

        <!-- Facilities & Hours -->
        <div class="row g-3 p-3 bg-light rounded">
            <div class="col-sm-6 col-md-3">
                <small class="text-muted d-block">Operating Hours</small>
                <strong>{{ date('h:i A', strtotime($location->opening_time)) }} - {{ date('h:i A', strtotime($location->closing_time)) }}</strong>
            </div>
            <div class="col-sm-6 col-md-3">
                <small class="text-muted d-block">Contact Number</small>
                <strong>{{ $location->contact_number ?? 'Not Available' }}</strong>
            </div>
            <div class="col-sm-6 col-md-3">
                <small class="text-muted d-block">Available Spots</small>
                <strong class="text-success">{{ $location->availableSlotsCount() }} Available / {{ $location->total_capacity }} Total</strong>
            </div>
            <div class="col-sm-6 col-md-3">
                <small class="text-muted d-block">Facilities</small>
                <span class="small fw-semibold text-dark">{{ $location->facilities ?? 'Standard Amenities' }}</span>
            </div>
        </div>
    </div>

    <!-- Available Zones Section (Requirement #19) -->
    <h4 class="fw-bold mb-3 text-dark">Available Parking Zones</h4>
    <p class="text-muted small mb-4">Select a parking zone to inspect real-time slot layout and reserve an available slot.</p>

    <div class="row g-4">
        @forelse($location->zones as $zone)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-4 border shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0 text-dark">{{ $zone->zone_name }}</h5>
                        <span class="badge bg-primary">{{ $zone->vehicle_type }}</span>
                    </div>

                    <p class="text-muted small mb-3">
                        Floor: <strong>{{ $zone->floor }}</strong> &bull; Section: {{ $zone->section ?? 'General' }}
                    </p>

                    <!-- Zone Slot Breakdown Stats -->
                    <div class="p-3 bg-light rounded mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Total Slots:</span>
                            <span class="fw-bold">{{ $zone->total_slots }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1 text-success">
                            <span>Available:</span>
                            <span class="fw-bold">{{ $zone->availableSlotsCount() }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1 text-primary">
                            <span>Reserved:</span>
                            <span class="fw-bold">{{ $zone->reservedSlotsCount() }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-danger">
                            <span>Occupied:</span>
                            <span class="fw-bold">{{ $zone->occupiedSlotsCount() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('user.parking.zone-slots', ['id' => $location->id, 'zone' => $zone->id]) }}" class="btn btn-primary w-100 fw-semibold mt-auto">
                        SELECT ZONE <i class="bi bi-grid-3x3-gap-fill ms-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 py-4 text-center text-muted">
                No zones currently created for this parking location.
            </div>
        @endforelse
    </div>
</div>
@endsection
