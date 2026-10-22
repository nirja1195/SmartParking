@extends(Auth::guard('staff')->check() ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Vehicle Entry Terminal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gate Entry Terminal</h3>
        <p class="text-muted mb-0">Confirm vehicle check-in: transitions slot from <span class="badge bg-primary">RESERVED</span> to <span class="badge bg-danger">OCCUPIED</span></p>
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
    <!-- Verification & Check-in Panel -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-qr-code-scan me-2 text-primary"></i> Scan / Search Vehicle</h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ Auth::guard('staff')->check() ? route('staff.entries.index') : route('admin.entries.index') }}" class="mb-4">
                    <label class="form-label fw-bold">Enter Booking ID or Vehicle License Number</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg text-uppercase font-monospace" placeholder="e.g. SP20260921001 or MH-12-AB-1234" value="{{ $search }}" required autofocus>
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                    <small class="text-muted">Enter confirmed booking reference or customer vehicle plate number.</small>
                </form>

                @if($foundBooking)
                    <div class="p-3 border rounded bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0 font-monospace">#{{ $foundBooking->booking_id }}</h6>
                            <span class="badge bg-primary fs-6">{{ $foundBooking->booking_status }}</span>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Vehicle Number</small>
                                <strong class="font-monospace fs-5">{{ $foundBooking->vehicle->vehicle_number ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Assigned Slot</small>
                                <span class="badge bg-success fs-5">{{ $foundBooking->slot->slot_number ?? 'N/A' }}</span>
                                <small class="text-muted">({{ $foundBooking->zone->zone_name ?? '' }})</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Customer</small>
                                <strong>{{ $foundBooking->user->name ?? 'Guest' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Time Window</small>
                                <span>{{ substr($foundBooking->start_time, 0, 5) }} - {{ substr($foundBooking->end_time, 0, 5) }}</span>
                            </div>
                        </div>

                        <!-- Confirm Form -->
                        <form action="{{ Auth::guard('staff')->check() ? route('staff.entries.confirm') : route('admin.entries.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="identifier" value="{{ $foundBooking->booking_id }}">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Entry Gate Remarks / Inspection Notes</label>
                                <input type="text" name="remarks" class="form-control form-control-sm" placeholder="e.g. Checked in via North Gate 1">
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> CONFIRM VEHICLE ENTRY
                            </button>
                        </form>
                    </div>
                @elseif($search)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-1"></i> No confirmed reservation found for <strong>{{ $search }}</strong> or vehicle has already entered.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Live Instructions -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-info-circle-fill me-2 text-primary"></i> Gate Staff Instructions</h5>
            </div>
            <div class="card-body p-4">
                <ol class="list-group list-group-numbered list-group-flush mb-3">
                    <li class="list-group-item px-0">Ask driver for their <strong>Booking ID</strong> (or scan the digital QR code from their mobile screen).</li>
                    <li class="list-group-item px-0">Verify the license plate on the vehicle matches the registered vehicle.</li>
                    <li class="list-group-item px-0">Press <strong>"CONFIRM VEHICLE ENTRY"</strong>.</li>
                    <li class="list-group-item px-0">The system automatically turns the slot <span class="badge bg-danger">OCCUPIED</span> and notifies the customer.</li>
                    <li class="list-group-item px-0">Direct the driver to the assigned parking bay (Zone & Slot number).</li>
                </ol>

                <div class="alert alert-primary mb-0 small">
                    <i class="bi bi-shield-lock-fill me-1"></i> All gate movements are logged with staff timestamp for security audits.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Gate Entries -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recent Gate Entries</h5>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Entry ID</th>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Slot</th>
                    <th>Entry Timestamp</th>
                    <th>Gate Staff</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEntries as $entry)
                    <tr>
                        <td class="font-monospace">#EN-{{ $entry->id }}</td>
                        <td class="fw-bold font-monospace text-primary">#{{ $entry->booking->booking_id ?? '-' }}</td>
                        <td class="font-monospace fw-bold">{{ $entry->vehicle->vehicle_number ?? '-' }}</td>
                        <td>
                            <span class="badge bg-danger">{{ $entry->slot->slot_number ?? '-' }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($entry->entry_time)->format('M d, Y h:i:s A') }}</td>
                        <td>{{ $entry->staff->name ?? 'Admin Terminal' }}</td>
                        <td class="text-muted small">{{ $entry->remarks ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No entry logs recorded yet today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
