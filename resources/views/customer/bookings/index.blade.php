@extends('layouts.customer')

@section('title', 'My Bookings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">My Reservations</h4>
        <p class="text-muted small mb-0">View current and past parking reservations</p>
    </div>
    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus me-1"></i> New Reservation
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Reference</th>
                    <th>Facility & Spot</th>
                    <th>Vehicle</th>
                    <th>Schedule</th>
                    <th>Total Fare</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <a href="{{ route('customer.bookings.show', $booking->booking_reference) }}" class="fw-bold text-decoration-none">
                                {{ $booking->booking_reference }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $booking->spot?->lot?->name }}</div>
                            <small class="text-muted">Spot: {{ $booking->spot?->spot_number }} ({{ $booking->spot?->floor }})</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $booking->vehicle?->license_plate ?? 'Default' }}
                            </span>
                        </td>
                        <td>
                            <div class="small">{{ $booking->start_time->format('M d, H:i') }} &rarr; {{ $booking->end_time->format('M d, H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">${{ number_format($booking->total_amount, 2) }}</div>
                        </td>
                        <td>
                            <span class="{{ booking_status_badge($booking->status) }}">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('customer.bookings.show', $booking->booking_reference) }}" class="btn btn-sm btn-outline-primary">
                                View Ticket
                            </a>
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('customer.bookings.cancel', $booking->booking_reference) }}" class="d-inline" onsubmit="return confirm('Cancel this reservation?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            You have no parking reservations yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
