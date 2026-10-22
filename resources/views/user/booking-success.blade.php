@extends('layouts.user')

@section('title', 'Booking Confirmed - #'.$booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 text-center">
        <!-- Success Card -->
        <div class="card p-4 p-md-5 shadow-sm border mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3 mx-auto" style="width: 72px; height: 72px;">
                <i class="bi bi-check-lg fs-1"></i>
            </div>

            <!-- Requirement #26: Success Headlines -->
            <h3 class="fw-bold text-success mb-1">Payment Successful</h3>
            <h5 class="fw-semibold text-dark mb-3">Booking Confirmed</h5>

            <div class="badge bg-light text-primary border px-3 py-2 fs-5 font-monospace mb-4">
                Booking ID: {{ $booking->booking_id }}
            </div>

            <!-- QR Code Section -->
            <div class="mb-4">
                <div class="d-inline-block p-2 bg-white border rounded shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($booking->booking_id) }}" 
                         alt="Gate Pass QR Code" 
                         width="160" 
                         height="160">
                </div>
                <small class="text-muted d-block mt-2">Scan at the parking gate terminal for automated entry check-in.</small>
            </div>

            <!-- Booking Breakdown -->
            <div class="p-3 bg-light rounded border text-start mb-4">
                <div class="row g-3 small">
                    <div class="col-6">
                        <span class="text-muted d-block">Parking Location</span>
                        <strong>{{ $booking->parkingLocation->name }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Zone & Slot</span>
                        <strong class="text-primary">{{ $booking->zone->zone_name }} &bull; Slot {{ $booking->slot->slot_number }}</strong>
                    </div>

                    <div class="col-6">
                        <span class="text-muted d-block">Vehicle</span>
                        <strong class="font-monospace">{{ $booking->vehicle->vehicle_number ?? 'Customer Vehicle' }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Reservation Date</span>
                        <strong>{{ $booking->booking_date->format('M d, Y') }}</strong>
                    </div>

                    <div class="col-6">
                        <span class="text-muted d-block">Scheduled Entry</span>
                        <strong>{{ $booking->entry_time->format('h:i A') }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Expected Exit</span>
                        <strong>{{ $booking->expected_exit_time->format('h:i A') }}</strong>
                    </div>

                    <div class="col-6">
                        <span class="text-muted d-block">Amount Settled</span>
                        <strong class="text-success fs-6">${{ number_format($booking->amount, 2) }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Payment ID</span>
                        <strong class="font-monospace">{{ $booking->payment->transaction_id ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Buttons: VIEW BOOKING & DOWNLOAD RECEIPT (Requirement #26) -->
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('user.booking.detail', $booking->id) }}" class="btn btn-outline-primary px-4 fw-semibold">
                    VIEW BOOKING
                </a>
                <a href="{{ route('user.booking.receipt', $booking->id) }}" class="btn btn-primary px-4 fw-semibold" target="_blank">
                    <i class="bi bi-printer me-1"></i> DOWNLOAD RECEIPT
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
