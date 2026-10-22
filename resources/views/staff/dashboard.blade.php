@extends('layouts.staff')

@section('title', 'Staff Gate Terminal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="fw-bold mb-0">Gate Operations Terminal</h3>
            <span class="badge bg-primary fs-6">{{ $staff->role ?? 'Gate Staff' }}</span>
        </div>
        <p class="text-muted mb-0">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Facility Assignment: 
            <strong>{{ $staff->parkingLocation->name ?? 'All Facility Gates (Roaming)' }}</strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staff.entries.index') }}" class="btn btn-success px-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Process Entry
        </a>
        <a href="{{ route('staff.exits.index') }}" class="btn btn-danger px-3">
            <i class="bi bi-box-arrow-right me-1"></i> Process Exit
        </a>
    </div>
</div>

<!-- Primary Action Quick Launch Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-success mb-2">INBOUND GATE</span>
                    <h4 class="fw-bold mb-1">Vehicle Check-In / Entry</h4>
                    <p class="text-muted small mb-3">Scan customer QR or enter Booking ID to mark slot as OCCUPIED</p>
                    <a href="{{ route('staff.entries.index') }}" class="btn btn-success">
                        <i class="bi bi-qr-code-scan me-1"></i> Open Entry Terminal &rarr;
                    </a>
                </div>
                <div class="display-3 text-success opacity-25">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-start border-4 border-danger h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-danger mb-2">OUTBOUND GATE</span>
                    <h4 class="fw-bold mb-1">Vehicle Check-Out / Exit</h4>
                    <p class="text-muted small mb-3">Confirm departure, calculate overtime penalties and mark slot AVAILABLE</p>
                    <a href="{{ route('staff.exits.index') }}" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Open Exit Terminal &rarr;
                    </a>
                </div>
                <div class="display-3 text-danger opacity-25">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Metrics Grid -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-danger-subtle text-danger me-3 fs-3">
                    <i class="bi bi-p-circle-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Active in Bays</div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $activeParkedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-primary-subtle text-primary me-3 fs-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Expected Arrivals</div>
                    <h3 class="fw-bold mb-0 text-primary">{{ $todayConfirmedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-success-subtle text-success me-3 fs-3">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Checked-In Today</div>
                    <h3 class="fw-bold mb-0 text-success">{{ $todayEntriesCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded bg-info-subtle text-info me-3 fs-3">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">Departed Today</div>
                    <h3 class="fw-bold mb-0 text-info">{{ $todayExitsCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Live Recent Gate Activity -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recent Gate Activity Logs</h5>
        <a href="{{ route('staff.entries.index') }}" class="btn btn-sm btn-outline-secondary">View Full Activity</a>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Entry ID</th>
                    <th>Booking ID</th>
                    <th>Vehicle Plate</th>
                    <th>Assigned Bay</th>
                    <th>Entry Timestamp</th>
                    <th>Gate Operator</th>
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
                            <small class="text-muted ms-1">{{ $entry->slot->parkingLocation->name ?? '' }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($entry->entry_time)->format('M d, Y h:i:s A') }}</td>
                        <td>{{ $entry->staff->name ?? 'Gate Operator' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No gate entries logged for this shift yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
