@extends('layouts.customer')

@section('title', 'Parking Ticket - '.$booking->booking_reference)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Reservations
            </a>
            <button onclick="window.print()" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-printer me-1"></i> Print Ticket
            </button>
        </div>

        <!-- Ticket Card -->
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="bg-dark text-white p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2 mb-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-p-square-fill fs-4"></i>
                </div>
                <h4 class="fw-bold mb-0">SmartPark Digital Ticket</h4>
                <p class="text-secondary small mb-0">Present at the gate scanner or security check</p>
                <div class="mt-3">
                    <span class="fs-4 font-monospace fw-bold bg-white text-dark px-3 py-1 rounded border">
                        #{{ $booking->booking_reference }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <span class="{{ booking_status_badge($booking->status) }} px-3 py-2 fs-6">
                        STATUS: {{ strtoupper($booking->status) }}
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <span class="text-muted small">Parking Facility</span>
                        <h5 class="fw-bold mb-0">{{ $booking->spot?->lot?->name }}</h5>
                        <small class="text-muted">{{ $booking->spot?->lot?->address }}, {{ $booking->spot?->lot?->city }}</small>
                    </div>

                    <div class="col-6 text-end">
                        <span class="text-muted small">Assigned Spot</span>
                        <h4 class="fw-bold text-primary mb-0">{{ $booking->spot?->spot_number }}</h4>
                        <span class="badge bg-secondary">{{ $booking->spot?->floor }} &bull; {{ strtoupper($booking->spot?->spot_type) }}</span>
                    </div>
                </div>

                <hr>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <span class="text-muted small">Valid From</span>
                        <div class="fw-semibold">{{ $booking->start_time->format('l, M d, Y') }}</div>
                        <div class="fw-bold text-dark">{{ $booking->start_time->format('H:i') }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted small">Valid Until</span>
                        <div class="fw-semibold">{{ $booking->end_time->format('l, M d, Y') }}</div>
                        <div class="fw-bold text-dark">{{ $booking->end_time->format('H:i') }}</div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded mb-4">
                    <div class="row">
                        <div class="col-6">
                            <span class="text-muted small">Vehicle</span>
                            <div class="fw-bold">{{ $booking->vehicle?->license_plate ?? 'Not registered' }}</div>
                            <small class="text-muted">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</small>
                        </div>
                        <div class="col-6 text-end">
                            <span class="text-muted small">Total Fare Settled</span>
                            <h4 class="fw-bold text-success mb-0">${{ number_format($booking->total_amount, 2) }}</h4>
                            @if($booking->payment)
                                <small class="text-muted">Ref: {{ $booking->payment->transaction_reference }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                @if(in_array($booking->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking->booking_reference) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            Cancel Reservation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
