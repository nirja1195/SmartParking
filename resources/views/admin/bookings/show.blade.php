@extends('layouts.admin')

@section('title', 'Booking #' . $booking->booking_id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="fw-bold mb-0">Booking #{{ $booking->booking_id }}</h3>
            <span class="badge 
                @if($booking->booking_status == 'CONFIRMED') bg-primary 
                @elseif($booking->booking_status == 'ACTIVE') bg-danger 
                @elseif($booking->booking_status == 'COMPLETED') bg-success 
                @elseif($booking->booking_status == 'CANCELLED') bg-secondary 
                @else bg-warning @endif fs-6">
                {{ $booking->booking_status }}
            </span>
        </div>
        <p class="text-muted mb-0">Created on {{ $booking->created_at->format('F d, Y \a\t h:i A') }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($booking->booking_status, ['PENDING', 'CONFIRMED']))
            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Cancel this booking and release the slot?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i> Cancel Booking
                </button>
            </form>
        @endif
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Bookings
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Main Details Column -->
    <div class="col-lg-8">
        <!-- Parking Bay & Allocation -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill me-2 text-primary"></i> Parking Bay & Schedule</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Parking Facility</div>
                        <h5 class="fw-bold mb-0">{{ $booking->parkingLocation->name ?? '-' }}</h5>
                        <small class="text-muted">{{ $booking->parkingLocation->address ?? '' }}, {{ $booking->parkingLocation->city ?? '' }}</small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Assigned Slot</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-light text-dark border fs-6">Zone {{ $booking->zone->zone_name ?? '-' }}</span>
                            <span class="badge bg-primary fs-5 font-monospace">{{ $booking->slot->slot_number ?? '-' }}</span>
                            <span class="badge bg-secondary-subtle text-secondary">{{ $booking->slot->status ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Scheduled Date</div>
                        <div class="fw-bold fs-6">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, M d, Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Time Window</div>
                        <div class="fw-bold fs-6 font-monospace">{{ substr($booking->start_time, 0, 5) }} &rarr; {{ substr($booking->end_time, 0, 5) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Total Duration</div>
                        <div class="fw-bold fs-6">{{ $booking->total_hours }} Hours</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gate Movements Timeline -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-shield-check me-2 text-primary"></i> Gate Check-in & Departure Logs</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded {{ $booking->parkingEntry ? 'bg-success-subtle border-success' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-box-arrow-in-right me-1 text-success"></i> Gate Entry</h6>
                                @if($booking->parkingEntry)
                                    <span class="badge bg-success">Recorded</span>
                                @else
                                    <span class="badge bg-secondary">Awaiting Entry</span>
                                @endif
                            </div>
                            @if($booking->parkingEntry)
                                <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->parkingEntry->entry_time)->format('M d, Y h:i A') }}</div>
                                <div><strong>Processed By:</strong> Staff #{{ $booking->parkingEntry->staff_id ?? 'Admin Terminal' }}</div>
                                @if($booking->parkingEntry->remarks)
                                    <small class="text-muted">Notes: {{ $booking->parkingEntry->remarks }}</small>
                                @endif
                            @else
                                <p class="text-muted small mb-0">Vehicle has not entered the parking facility yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded {{ $booking->parkingExit ? 'bg-info-subtle border-info' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-box-arrow-right me-1 text-info"></i> Gate Exit</h6>
                                @if($booking->parkingExit)
                                    <span class="badge bg-info">Recorded</span>
                                @else
                                    <span class="badge bg-secondary">Pending Departure</span>
                                @endif
                            </div>
                            @if($booking->parkingExit)
                                <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->parkingExit->exit_time)->format('M d, Y h:i A') }}</div>
                                <div><strong>Penalty Fee:</strong> ${{ number_format($booking->parkingExit->penalty_charge, 2) }}</div>
                                @if($booking->parkingExit->remarks)
                                    <small class="text-muted">Notes: {{ $booking->parkingExit->remarks }}</small>
                                @endif
                            @else
                                <p class="text-muted small mb-0">Vehicle is currently parked or awaiting entry.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar Column -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-fill me-2 text-primary"></i> Customer Information</h6>
            </div>
            <div class="card-body p-3">
                <div class="fw-bold fs-6">{{ $booking->user->name ?? 'Guest User' }}</div>
                <div class="text-muted small mb-2"><i class="bi bi-envelope me-1"></i> {{ $booking->user->email ?? 'N/A' }}</div>
                <div class="text-muted small mb-3"><i class="bi bi-telephone me-1"></i> {{ $booking->user->phone ?? 'N/A' }}</div>
                <hr class="my-2">
                <div class="text-muted small">Registered Vehicle:</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-secondary">{{ $booking->vehicle->vehicle_type ?? 'Vehicle' }}</span>
                    <span class="font-monospace fw-bold fs-6">{{ $booking->vehicle->vehicle_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-credit-card-2-front-fill me-2 text-primary"></i> Billing Summary</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Parking Fee:</span>
                    <span>${{ number_format($booking->parking_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Convenience Fee:</span>
                    <span>${{ number_format($booking->convenience_fee, 2) }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total Paid:</span>
                    <span class="fw-bold fs-5 text-success">${{ number_format($booking->total_price, 2) }}</span>
                </div>

                @if($booking->payment)
                    <div class="bg-light p-3 rounded small">
                        <div><strong>Payment Method:</strong> {{ $booking->payment->payment_method }}</div>
                        <div class="font-monospace text-truncate"><strong>Txn Ref:</strong> {{ $booking->payment->transaction_id }}</div>
                        <div><strong>Status:</strong> <span class="badge bg-success">{{ $booking->payment->status }}</span></div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0 py-2 small">Payment record not found.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
