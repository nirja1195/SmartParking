@extends(Auth::guard('staff')->check() ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Vehicle Exit Terminal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gate Exit Terminal</h3>
        <p class="text-muted mb-0">Confirm vehicle departure: transitions slot from <span class="badge bg-danger">OCCUPIED</span> back to <span class="badge bg-success">AVAILABLE</span></p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- Verification & Check-out Panel -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-box-arrow-right me-2 text-danger"></i> Departure Verification</h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ Auth::guard('staff')->check() ? route('staff.exits.index') : route('admin.exits.index') }}" class="mb-4">
                    <label class="form-label fw-bold">Enter Booking ID or Vehicle License Number</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg text-uppercase font-monospace" placeholder="e.g. SP20260921001 or MH-12-AB-1234" value="{{ $search }}" required autofocus>
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                    <small class="text-muted">Enter active booking reference or vehicle plate number currently parked.</small>
                </form>

                @if($foundBooking)
                    <div class="p-3 border rounded bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0 font-monospace">#{{ $foundBooking->booking_id }}</h6>
                            <span class="badge bg-danger fs-6">{{ $foundBooking->booking_status }}</span>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Vehicle Number</small>
                                <strong class="font-monospace fs-5">{{ $foundBooking->vehicle->vehicle_number ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Occupied Slot</small>
                                <span class="badge bg-danger fs-5">{{ $foundBooking->slot->slot_number ?? 'N/A' }}</span>
                                <small class="text-muted">({{ $foundBooking->zone->zone_name ?? '' }})</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Customer</small>
                                <strong>{{ $foundBooking->user->name ?? 'Guest' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Booked Until</small>
                                <span class="font-monospace text-danger fw-bold">{{ substr($foundBooking->end_time, 0, 5) }}</span>
                            </div>
                        </div>

                        <!-- Confirm Exit Form -->
                        <form action="{{ Auth::guard('staff')->check() ? route('staff.exits.confirm') : route('admin.exits.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="identifier" value="{{ $foundBooking->booking_id }}">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Exit Gate Remarks / Barrier Gate Notes</label>
                                <input type="text" name="remarks" class="form-control form-control-sm" placeholder="e.g. Exit confirmed. Barrier opened.">
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                                <i class="bi bi-box-arrow-right me-2"></i> CONFIRM VEHICLE EXIT & RELEASE SLOT
                            </button>
                        </form>
                    </div>
                @elseif($search)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-1"></i> No active parked vehicle found for <strong>{{ $search }}</strong> or vehicle has already departed.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Exit Policies -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-cash-coin me-2 text-primary"></i> Automated Overtime Billing</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted">When confirming an exit, the system automatically inspects the departure timestamp against the booked time window:</p>

                <ul class="list-unstyled mb-3">
                    <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> <strong>On Time Departure:</strong> No penalty charge applied. Slot immediately flips to <span class="badge bg-success">AVAILABLE</span>.</li>
                    <li class="mb-2"><i class="bi bi-exclamation-circle text-warning me-2"></i> <strong>Overtime Grace Period:</strong> 15 minutes grace period allowed.</li>
                    <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i> <strong>Overstay Penalty:</strong> Overtime is billed at 1.5x hourly rate per extra hour.</li>
                </ul>

                <div class="alert alert-success mb-0 small">
                    <i class="bi bi-check-circle-fill me-1"></i> Customer receipt will instantly be updated with final exit timestamp and downloadable in their portal.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Gate Exits -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recent Gate Departures</h5>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Exit ID</th>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Released Slot</th>
                    <th>Exit Timestamp</th>
                    <th>Penalty</th>
                    <th>Gate Staff</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentExits as $exit)
                    <tr>
                        <td class="font-monospace">#EX-{{ $exit->id }}</td>
                        <td class="fw-bold font-monospace text-primary">#{{ $exit->booking->booking_id ?? '-' }}</td>
                        <td class="font-monospace fw-bold">{{ $exit->vehicle->vehicle_number ?? '-' }}</td>
                        <td>
                            <span class="badge bg-success">{{ $exit->slot->slot_number ?? '-' }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($exit->exit_time)->format('M d, Y h:i:s A') }}</td>
                        <td>
                            @if($exit->penalty_charge > 0)
                                <span class="badge bg-danger">${{ number_format($exit->penalty_charge, 2) }}</span>
                            @else
                                <span class="badge bg-light text-muted border">$0.00</span>
                            @endif
                        </td>
                        <td>{{ $exit->staff->name ?? 'Admin Terminal' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No exit logs recorded yet today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
