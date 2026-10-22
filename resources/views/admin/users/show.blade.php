@extends('layouts.admin')

@section('title', 'Customer Profile: ' . $user->name)
@section('page-title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
        <p class="text-muted mb-0">Customer ID #{{ $user->id }} &bull; Registered {{ $user->created_at->format('M d, Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Account
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- User Overview -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 text-center mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem; font-weight: bold;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <div class="text-muted mb-2">{{ $user->email }}</div>
            <div>
                <span class="badge 
                    @if($user->status == 'active') bg-success 
                    @elseif($user->status == 'blocked') bg-danger 
                    @else bg-secondary @endif px-3 py-1 fs-6">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
            <hr class="my-3">
            <div class="text-start small text-muted">
                <div class="d-flex justify-content-between py-1">
                    <span>Phone:</span>
                    <strong class="text-dark">{{ $user->phone ?? 'Not provided' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Address:</span>
                    <strong class="text-dark">{{ $user->address ?? 'Not provided' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Total Bookings:</span>
                    <strong class="text-primary">{{ $user->bookings->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Vehicles:</span>
                    <strong class="text-dark">{{ $user->vehicles->count() }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-car-front me-2 text-primary"></i> Registered Vehicles ({{ $user->vehicles->count() }})</h6>
                <a href="{{ route('admin.vehicles.index', ['search' => $user->name]) }}" class="btn btn-sm btn-outline-primary">Manage Fleet</a>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>License Plate</th>
                            <th>Type</th>
                            <th>Brand & Model</th>
                            <th>Color</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->vehicles as $vehicle)
                            <tr>
                                <td>
                                    <span class="badge bg-dark font-monospace px-2 py-1 text-warning">
                                        {{ $vehicle->vehicle_number }}
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $vehicle->vehicle_type }}</span></td>
                                <td>{{ $vehicle->brand ? $vehicle->brand . ' ' . $vehicle->model : ($vehicle->model ?? '-') }}</td>
                                <td>{{ $vehicle->color ?? '-' }}</td>
                                <td class="text-muted">{{ $vehicle->created_at ? $vehicle->created_at->format('M d, Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">No vehicles registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recent Bookings</h6>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Location</th>
                            <th>Slot</th>
                            <th>Vehicle</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->bookings as $booking)
                            <tr>
                                <td class="font-monospace fw-bold text-primary">#{{ $booking->booking_id }}</td>
                                <td>{{ $booking->parkingLocation->name ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $booking->slot->slot_number ?? '-' }}</span></td>
                                <td class="font-monospace">{{ $booking->vehicle->vehicle_number ?? '-' }}</td>
                                <td class="fw-bold text-success">${{ number_format($booking->total_price, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($booking->booking_status == 'CONFIRMED') bg-primary 
                                        @elseif($booking->booking_status == 'ACTIVE') bg-danger 
                                        @elseif($booking->booking_status == 'COMPLETED') bg-success 
                                        @else bg-secondary @endif">
                                        {{ $booking->booking_status }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">No bookings recorded for this user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
