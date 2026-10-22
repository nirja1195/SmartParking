@extends('layouts.customer')

@section('title', 'Customer Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Active Booking Banner (if present) -->
    @if($activeBooking)
        <div class="col-12">
            <div class="card bg-primary text-white p-4 shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-primary fw-bold">ACTIVE RESERVATION</span>
                            <span class="small opacity-75">Ref: #{{ $activeBooking->booking_reference }}</span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $activeBooking->spot?->lot?->name }}</h3>
                        <p class="mb-2 opacity-90">
                            <strong>Spot: {{ $activeBooking->spot?->spot_number }}</strong> ({{ $activeBooking->spot?->floor }}) &bull;
                            Vehicle: {{ $activeBooking->vehicle?->license_plate ?? 'Default' }} &bull;
                            Valid until: {{ $activeBooking->end_time->format('M d, H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('customer.bookings.show', $activeBooking->booking_reference) }}" class="btn btn-light text-primary fw-bold px-4 py-2">
                            <i class="bi bi-qr-code me-1"></i> View Digital Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL RESERVATIONS</span>
                    <h3 class="fw-bold mb-0">{{ $stats['total_bookings'] }}</h3>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-circle fs-4">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">MY VEHICLES</span>
                    <h3 class="fw-bold mb-0">{{ $stats['total_vehicles'] }}</h3>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-circle fs-4">
                    <i class="bi bi-car-front"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL SPENT</span>
                    <h3 class="fw-bold mb-0">${{ number_format($stats['total_spent'], 2) }}</h3>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-circle fs-4">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Available Parking Facilities -->
    <div class="col-lg-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Nearby Parking Garages</h5>
                <a href="{{ route('customer.bookings.create') }}" class="btn btn-sm btn-primary">Book a Spot</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($availableLots as $lot)
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $lot->name }}</h6>
                            <p class="text-muted small mb-0">{{ $lot->address }}, {{ $lot->city }}</p>
                            <small class="text-success fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ $lot->available_spots_count }} vacant spots available
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-5">${{ number_format($lot->hourly_rate, 2) }}<small class="text-muted fs-6">/hr</small></div>
                            <a href="{{ route('customer.bookings.create', ['lot_id' => $lot->id]) }}" class="btn btn-sm btn-outline-primary mt-1">
                                Reserve
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted py-3">No active parking facilities found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-5">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Bookings</h5>
                <a href="{{ route('customer.bookings.index') }}" class="small text-decoration-none">All History</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentBookings as $b)
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold small">{{ $b->spot?->lot?->name }}</span>
                            <span class="{{ booking_status_badge($b->status) }}">{{ strtoupper($b->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mt-1">
                            <span>Spot {{ $b->spot?->spot_number }} &bull; {{ $b->start_time->format('M d, H:i') }}</span>
                            <span class="fw-bold text-dark">${{ number_format($b->total_amount, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small py-3 mb-0">No past bookings yet. Reserve your first spot today!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
