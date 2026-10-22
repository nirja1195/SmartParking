@extends('layouts.user')

@section('title', 'Booking #'.$booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('user.my-bookings') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to My Bookings
            </a>
            <a href="{{ route('user.booking.receipt', $booking->id) }}" class="btn btn-outline-dark btn-sm" target="_blank">
                <i class="bi bi-printer me-1"></i> View / Print Digital Receipt
            </a>
        </div>

        <div class="card p-4 shadow-sm border mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <span class="badge bg-light text-primary border font-monospace px-3 py-1 fs-6 mb-1">
                        #{{ $booking->booking_id }}
                    </span>
                    <h4 class="fw-bold text-dark mb-0">{{ $booking->parkingLocation->name }}</h4>
                    <small class="text-muted">{{ $booking->parkingLocation->address }}, {{ $booking->parkingLocation->city }}</small>
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
                    <span class="badge {{ $badge }} fs-6 px-3 py-2 mb-1">{{ $booking->booking_status }}</span>
                    <small class="text-success d-block fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> {{ $booking->payment_status }}</small>
                </div>
            </div>

            <!-- Progress Timeline (Requirement #28: Booked -> Confirmed -> Entry -> Active -> Exit -> Completed) -->
            <div class="p-3 bg-light rounded border mb-4">
                <h6 class="fw-bold text-dark mb-3">Lifecycle Progress</h6>
                <div class="d-flex justify-content-between text-center position-relative">
                    <div class="flex-fill">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="small fw-semibold">Booked</div>
                    </div>
                    <div class="flex-fill">
                        <div class="rounded-circle {{ in_array($booking->booking_status, ['CONFIRMED', 'ACTIVE', 'COMPLETED']) ? 'bg-success text-white' : 'bg-secondary text-white' }} d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                            <i class="bi bi-check"></i>
                        </div>
                        <div class="small fw-semibold">Confirmed</div>
                    </div>
                    <div class="flex-fill">
                        <div class="rounded-circle {{ in_array($booking->booking_status, ['ACTIVE', 'COMPLETED']) ? 'bg-success text-white' : 'bg-secondary text-white' }} d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div class="small fw-semibold">Entry</div>
                    </div>
                    <div class="flex-fill">
                        <div class="rounded-circle {{ in_array($booking->booking_status, ['ACTIVE', 'COMPLETED']) ? 'bg-success text-white' : 'bg-secondary text-white' }} d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <div class="small fw-semibold">Active</div>
                    </div>
                    <div class="flex-fill">
                        <div class="rounded-circle {{ $booking->booking_status === 'COMPLETED' ? 'bg-success text-white' : 'bg-secondary text-white' }} d-inline-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                            <i class="bi bi-flag-fill"></i>
                        </div>
                        <div class="small fw-semibold">Completed</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- QR Code Card -->
                <div class="col-md-5 text-center">
                    <div class="p-3 bg-light rounded border h-100 d-flex flex-column align-items-center justify-content-center">
                        <span class="text-muted small fw-semibold mb-2">Gate Pass QR Code</span>
                        <div class="bg-white p-2 border rounded shadow-sm mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($booking->booking_id) }}" alt="QR Code" width="150" height="150">
                        </div>
                        <span class="font-monospace small fw-bold text-dark">{{ $booking->booking_id }}</span>
                        <small class="text-muted mt-1">Scan at entrance scanner</small>
                    </div>
                </div>

                <!-- Detailed Parameters -->
                <div class="col-md-7">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Zone & Assigned Slot</span>
                            <strong class="text-primary fs-6">{{ $booking->zone->zone_name }} &bull; Slot {{ $booking->slot->slot_number }}</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="text-muted d-block">Vehicle</span>
                            <strong class="font-monospace fs-6">{{ $booking->vehicle->vehicle_number ?? 'Customer' }}</strong>
                        </div>

                        <div class="col-6 mt-2">
                            <span class="text-muted d-block">Scheduled Entry</span>
                            <strong>{{ $booking->entry_time->format('M d, Y - h:i A') }}</strong>
                        </div>
                        <div class="col-6 mt-2 text-end">
                            <span class="text-muted d-block">Expected Exit</span>
                            <strong>{{ $booking->expected_exit_time->format('M d, Y - h:i A') }}</strong>
                        </div>

                        @if($booking->parkingEntry)
                            <div class="col-6 mt-2 text-success">
                                <span class="text-muted d-block">Actual Gate Entry</span>
                                <strong>{{ $booking->parkingEntry->entry_time->format('M d, Y - h:i A') }}</strong>
                            </div>
                        @endif

                        @if($booking->parkingExit)
                            <div class="col-6 mt-2 text-end text-success">
                                <span class="text-muted d-block">Actual Gate Exit</span>
                                <strong>{{ $booking->parkingExit->exit_time->format('M d, Y - h:i A') }}</strong>
                            </div>
                        @endif

                        <div class="col-12 mt-3 pt-2 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Paid Amount:</span>
                                <span class="fw-bold fs-5 text-success">${{ number_format($booking->amount, 2) }}</span>
                            </div>
                            @if($booking->payment)
                                <small class="text-muted d-block">Payment Method: {{ $booking->payment->payment_method }} (Ref: {{ $booking->payment->transaction_id }})</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel Button if Confirmed -->
            @if($booking->booking_status === 'CONFIRMED')
                <div class="border-top pt-3 d-flex justify-content-end">
                    <form method="POST" action="{{ route('user.booking.cancel', $booking->id) }}" onsubmit="return confirm('Cancel this reservation?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Cancel Booking</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
