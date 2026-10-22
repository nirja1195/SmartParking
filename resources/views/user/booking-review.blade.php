@extends('layouts.user')

@section('title', 'Booking Review')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4 shadow-sm border">
            <h4 class="fw-bold mb-2 text-dark">Review Your Reservation</h4>
            <p class="text-muted small mb-4">Please verify all details below before proceeding to payment confirmation.</p>

            <form method="POST" action="{{ route('user.booking.store') }}">
                @csrf
                <input type="hidden" name="parking_location_id" value="{{ $location->id }}">
                <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle?->id }}">
                <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
                <input type="hidden" name="entry_time" value="{{ $entryTime->toDateTimeString() }}">
                <input type="hidden" name="expected_exit_time" value="{{ $exitTime->toDateTimeString() }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted small d-block">Parking Facility</span>
                            <h5 class="fw-bold text-dark mb-1">{{ $location->name }}</h5>
                            <small class="text-muted">{{ $location->address }}, {{ $location->city }}</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted small d-block">Zone & Assigned Slot</span>
                            <h5 class="fw-bold text-primary mb-1">Slot {{ $slot->slot_number }}</h5>
                            <small class="text-muted">{{ $zone->zone_name }} &bull; Floor {{ $slot->floor }}</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted small d-block">Vehicle Assigned</span>
                            <h6 class="fw-bold text-dark mb-1 font-monospace">{{ $vehicle ? $vehicle->vehicle_number : 'Customer Vehicle' }}</h6>
                            <small class="text-muted">{{ $vehicle ? ($vehicle->vehicle_make ?? $vehicle->vehicle_type) : 'Standard' }}</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted small d-block">Reservation Date</span>
                            <h6 class="fw-bold text-dark mb-1">{{ Carbon\Carbon::parse($bookingDate)->format('l, M d, Y') }}</h6>
                            <small class="text-muted">Single Day Reservation</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted small d-block">Scheduled Entry Time</span>
                            <strong class="text-dark">{{ $entryTime->format('h:i A') }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted small d-block">Expected Exit Time</span>
                            <strong class="text-dark">{{ $exitTime->format('h:i A') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Price Calculation Box -->
                <div class="card bg-light border p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Reserved Duration:</span>
                        <span class="fw-semibold">{{ $durationHours }} Hour(s)</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Hourly Rate:</span>
                        <span class="fw-semibold">${{ number_format($hourlyRate, 2) }} / hr</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-dark">Total Payable Amount:</span>
                        <span class="fw-bold fs-4 text-success">${{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>

                <!-- Action Buttons: BACK & PROCEED TO PAYMENT (Requirement #24) -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.parking.zone-slots', ['id' => $location->id, 'zone' => $zone->id]) }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> BACK
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        PROCEED TO PAYMENT <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
