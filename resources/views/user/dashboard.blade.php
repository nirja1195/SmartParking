@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Active Parking Card (Requirement #30: Active Parking) -->
    @if($activeParking)
        <div class="col-12">
            <div class="card border-success shadow-sm" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #ffffff;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-white text-success fw-bold px-3 py-1">ACTIVE PARKING</span>
                                <span class="badge bg-success-subtle text-white font-monospace">STATUS: PARKED</span>
                                <span class="small opacity-75">#{{ $activeParking->booking_id }}</span>
                            </div>
                            <h3 class="fw-bold mb-1">{{ $activeParking->parkingLocation->name }}</h3>
                            <p class="mb-2 opacity-90">
                                <strong>Zone: {{ $activeParking->zone->zone_name }} &bull; Slot: {{ $activeParking->slot->slot_number }}</strong> (Floor: {{ $activeParking->slot->floor }})
                            </p>
                            <div class="d-flex flex-wrap gap-3 small opacity-90">
                                <span><i class="bi bi-car-front me-1"></i> Vehicle: {{ $activeParking->vehicle->vehicle_number ?? 'Registered' }}</span>
                                <span><i class="bi bi-box-arrow-in-right me-1"></i> Entry: {{ $activeParking->entry_time ? $activeParking->entry_time->format('h:i A') : 'Recorded' }}</span>
                                <span><i class="bi bi-clock me-1"></i> Expected Exit: {{ $activeParking->expected_exit_time->format('h:i A') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('user.booking.detail', $activeParking->id) }}" class="btn btn-light text-success fw-bold px-4 py-2">
                                <i class="bi bi-qr-code me-1"></i> Digital Ticket / QR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Upcoming Booking Card -->
    @if($upcomingBooking && !$activeParking)
        <div class="col-12">
            <div class="card border-primary" style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <span class="badge bg-white text-primary fw-bold mb-2">UPCOMING RESERVATION</span>
                            <h4 class="fw-bold mb-1">{{ $upcomingBooking->parkingLocation->name }}</h4>
                            <p class="mb-0 opacity-90">
                                Slot {{ $upcomingBooking->slot->slot_number }} ({{ $upcomingBooking->zone->zone_name }}) &bull;
                                Today at {{ $upcomingBooking->entry_time->format('h:i A') }}
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('user.booking.detail', $upcomingBooking->id) }}" class="btn btn-light text-primary fw-bold">
                                View QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Locations Widget -->
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Available Parking Facilities</h5>
                    <small class="text-muted">Real-time availability calculated dynamically from MySQL</small>
                </div>
                <a href="{{ route('user.find-parking') }}" class="btn btn-sm btn-primary">Find Parking</a>
            </div>

            <div class="row g-3">
                @forelse($availableLocations as $loc)
                    <div class="col-md-6">
                        <div class="card border h-100 p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">{{ $loc->name }}</h6>
                                <span class="badge bg-success">${{ number_format($loc->parking_charges, 2) }}/hr</span>
                            </div>
                            <p class="text-muted small mb-2">{{ $loc->address }}, {{ $loc->city }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="small fw-semibold text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ $loc->available_slots_count }} Slots Available
                                </span>
                                <a href="{{ route('user.parking.show', $loc->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4 text-center text-muted">
                        No active parking locations found in database.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- My Vehicles Widget -->
    <div class="col-lg-4">
        <div class="card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">My Vehicles</h5>
                <a href="{{ route('user.vehicles') }}" class="small text-decoration-none">+ Manage</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($vehicles as $veh)
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold font-monospace">{{ $veh->vehicle_number }}</div>
                            <small class="text-muted">{{ $veh->vehicle_model }} ({{ $veh->vehicle_type }})</small>
                        </div>
                        @if($veh->is_default)
                            <span class="badge bg-primary-subtle text-primary">Default</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small py-2 mb-0">No registered vehicles yet. <a href="{{ route('user.vehicles') }}">Add vehicle</a></p>
                @endforelse
            </div>
        </div>

        <!-- Notifications Widget -->
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Notifications</h5>
                <a href="{{ route('user.notifications') }}" class="small text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($notifications as $notif)
                    <div class="list-group-item px-0 py-2">
                        <div class="fw-semibold small text-dark">{{ $notif->title }}</div>
                        <small class="text-muted d-block">{{ Str::limit($notif->message, 60) }}</small>
                    </div>
                @empty
                    <p class="text-muted small py-2 mb-0">No new notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Table -->
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Recent Reservations</h5>
        <a href="{{ route('user.my-bookings') }}" class="small text-decoration-none">All Bookings</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Facility</th>
                    <th>Zone & Slot</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                    <tr>
                        <td class="fw-bold text-primary font-monospace">{{ $b->booking_id }}</td>
                        <td>{{ $b->parkingLocation->name }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $b->slot->slot_number }}
                            </span>
                        </td>
                        <td>
                            <div class="small">{{ $b->booking_date->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $b->entry_time->format('h:i A') }}</small>
                        </td>
                        <td class="fw-bold">${{ number_format($b->amount, 2) }}</td>
                        <td>
                            @php
                                $badge = match($b->booking_status) {
                                    'CONFIRMED' => 'bg-primary',
                                    'ACTIVE' => 'bg-success',
                                    'COMPLETED' => 'bg-secondary',
                                    'CANCELLED' => 'bg-danger',
                                    default => 'bg-light text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $b->booking_status }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('user.booking.detail', $b->id) }}" class="btn btn-sm btn-outline-primary">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No past reservations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
