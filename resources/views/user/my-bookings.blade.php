@extends('layouts.user')

@section('title', 'My Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">My Parking Bookings</h4>
        <p class="text-muted small mb-0">Track and manage your upcoming, active, and completed parking sessions</p>
    </div>
    <a href="{{ route('user.find-parking') }}" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> New Booking
    </a>
</div>

<!-- Tabs (Requirement #27: All, Upcoming, Active, Completed, Cancelled) -->
<ul class="nav nav-pills mb-4 gap-2">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'all' ? 'active' : 'bg-white border' }}" href="{{ route('user.my-bookings', ['tab' => 'all']) }}">All Bookings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'upcoming' ? 'active' : 'bg-white border' }}" href="{{ route('user.my-bookings', ['tab' => 'upcoming']) }}">Upcoming</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'active' ? 'active' : 'bg-white border' }}" href="{{ route('user.my-bookings', ['tab' => 'active']) }}">Active Parking</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'completed' ? 'active' : 'bg-white border' }}" href="{{ route('user.my-bookings', ['tab' => 'completed']) }}">Completed</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'cancelled' ? 'active' : 'bg-white border' }}" href="{{ route('user.my-bookings', ['tab' => 'cancelled']) }}">Cancelled</a>
    </li>
</ul>

<!-- Bookings Cards -->
<div class="row g-4">
    @forelse($bookings as $booking)
        <div class="col-lg-6">
            <div class="card p-4 h-100 shadow-sm border">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="font-monospace fw-bold text-primary fs-5">#{{ $booking->booking_id }}</span>
                        <h5 class="fw-bold text-dark mt-1 mb-0">{{ $booking->parkingLocation->name }}</h5>
                        <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $booking->parkingLocation->address }}, {{ $booking->parkingLocation->city }}</small>
                    </div>
                    <div class="text-end">
                        @php
                            $badge = match($booking->booking_status) {
                                'CONFIRMED' => 'bg-primary',
                                'ACTIVE' => 'bg-success',
                                'COMPLETED' => 'bg-secondary',
                                'CANCELLED' => 'bg-danger',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <span class="badge {{ $badge }} px-3 py-1 mb-1 d-block">{{ $booking->booking_status }}</span>
                        <span class="badge bg-light text-muted border">{{ $booking->payment_status }}</span>
                    </div>
                </div>

                <hr class="my-2">

                <div class="row g-2 small my-2">
                    <div class="col-6">
                        <span class="text-muted d-block">Zone & Assigned Slot:</span>
                        <strong class="text-dark">{{ $booking->zone->zone_name }} &bull; Slot {{ $booking->slot->slot_number }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Vehicle Assigned:</span>
                        <strong class="font-monospace">{{ $booking->vehicle->vehicle_number ?? 'Customer' }}</strong>
                    </div>

                    <div class="col-6">
                        <span class="text-muted d-block">Date:</span>
                        <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Time Interval:</span>
                        <strong>{{ $booking->entry_time->format('h:i A') }} - {{ $booking->expected_exit_time->format('h:i A') }}</strong>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="p-2 bg-light rounded d-flex justify-content-between align-items-center">
                            <span class="text-muted">Total Fare:</span>
                            <span class="fw-bold text-success fs-6">${{ number_format($booking->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions: VIEW DETAILS, CANCEL BOOKING, BOOK AGAIN (Requirement #27) -->
                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top gap-2">
                    <a href="{{ route('user.booking.detail', $booking->id) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                        VIEW DETAILS
                    </a>

                    <div class="d-flex gap-2">
                        @if($booking->booking_status === 'CONFIRMED')
                            <form method="POST" action="{{ route('user.booking.cancel', $booking->id) }}" onsubmit="return confirm('Cancel this reservation?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    CANCEL BOOKING
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('user.parking.show', $booking->parking_location_id) }}" class="btn btn-sm btn-primary">
                            BOOK AGAIN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center text-muted">
            <i class="bi bi-calendar-x fs-1"></i>
            <h5 class="fw-bold mt-2 text-dark">No Bookings Found</h5>
            <p class="small">You have no reservations under the "{{ strtoupper($tab) }}" filter.</p>
            <a href="{{ route('user.find-parking') }}" class="btn btn-primary btn-sm mt-1">Find & Book a Spot</a>
        </div>
    @endforelse
</div>

@if($bookings->hasPages())
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
@endif
@endsection
