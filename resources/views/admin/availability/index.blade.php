@extends('layouts.admin')

@section('title', 'Live Parking Availability Monitor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Live Parking Availability Monitor</h3>
        <p class="text-muted mb-0">High-level real-time visual status of every parking bay across all managed facilities</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-primary" onclick="window.location.reload();">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Grid
        </button>
    </div>
</div>

<!-- Global Summary Bar -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <small class="text-muted fw-bold">TOTAL SLOTS</small>
            <h3 class="fw-bold mb-0 text-dark">{{ $globalStats['total'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3" style="border-bottom: 3px solid #10b981 !important;">
            <small class="text-success fw-bold">AVAILABLE</small>
            <h3 class="fw-bold mb-0 text-success">{{ $globalStats['available'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3" style="border-bottom: 3px solid #2563eb !important;">
            <small class="text-primary fw-bold">RESERVED</small>
            <h3 class="fw-bold mb-0 text-primary">{{ $globalStats['reserved'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3" style="border-bottom: 3px solid #ef4444 !important;">
            <small class="text-danger fw-bold">OCCUPIED</small>
            <h3 class="fw-bold mb-0 text-danger">{{ $globalStats['occupied'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3" style="border-bottom: 3px solid #6b7280 !important;">
            <small class="text-secondary fw-bold">MAINTENANCE</small>
            <h3 class="fw-bold mb-0 text-secondary">{{ $globalStats['maintenance'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm text-center p-3" style="border-bottom: 3px solid #1f2937 !important;">
            <small class="text-dark fw-bold">BLOCKED</small>
            <h3 class="fw-bold mb-0 text-dark">{{ $globalStats['blocked'] }}</h3>
        </div>
    </div>
</div>

<!-- Color Legend -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background-color: #10b981;"></span>
                    <span class="small fw-semibold">Available (Open for booking)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background-color: #2563eb;"></span>
                    <span class="small fw-semibold">Reserved (Booked online)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background-color: #ef4444;"></span>
                    <span class="small fw-semibold">Occupied (Vehicle parked)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background-color: #6b7280;"></span>
                    <span class="small fw-semibold">Maintenance</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle d-inline-block" style="width: 14px; height: 14px; background-color: #1f2937;"></span>
                    <span class="small fw-semibold">Blocked</span>
                </div>
            </div>
            <div>
                <span class="badge bg-dark-subtle text-dark border p-2">Global Occupancy Rate: <strong>{{ $globalStats['occupancy_rate'] }}%</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Facilities & Zones Grid -->
<div class="row g-4">
    @forelse($locations as $loc)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-geo-alt-fill me-2"></i> {{ $loc->name }}</h5>
                        <small class="text-muted">{{ $loc->address }}, {{ $loc->city }}</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.parking-locations.show', $loc->id) }}" class="btn btn-sm btn-outline-secondary">
                            View Facility
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @forelse($loc->zones as $zone)
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold mb-0">
                                    <span class="badge bg-light text-dark border me-2">Zone {{ $zone->zone_name }}</span>
                                    <span>{{ $zone->vehicle_type }} Parking</span>
                                    <span class="text-muted small ms-1">({{ $zone->floor }})</span>
                                </h6>
                                <small class="text-muted">{{ $zone->slots->count() }} Slots</small>
                            </div>

                            <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded">
                                @forelse($zone->slots as $slot)
                                    @php
                                        $color = match($slot->status) {
                                            'AVAILABLE' => '#10b981',
                                            'RESERVED' => '#2563eb',
                                            'OCCUPIED' => '#ef4444',
                                            'MAINTENANCE' => '#6b7280',
                                            'BLOCKED' => '#1f2937',
                                            default => '#94a3b8'
                                        };
                                    @endphp
                                    <div class="p-2 rounded text-white text-center font-monospace fw-bold shadow-sm" 
                                         style="background-color: {{ $color }}; min-width: 65px; cursor: default;" 
                                         title="Slot: {{ $slot->slot_number }} | Status: {{ $slot->status }}">
                                        <div style="font-size: 0.7rem; opacity: 0.85;">{{ substr($slot->status, 0, 3) }}</div>
                                        <div class="fs-6">{{ $slot->slot_number }}</div>
                                    </div>
                                @empty
                                    <small class="text-muted">No slots generated in this zone yet.</small>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No zones created in this facility.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No active facilities found.</p>
        </div>
    @endforelse
</div>
@endsection
