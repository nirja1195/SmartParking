@extends('layouts.admin')

@section('title', 'Reports & Financial Statements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
    <div>
        <h3 class="fw-bold mb-1">Operational & Revenue Reports</h3>
        <p class="text-muted mb-0">Generate daily, monthly, facility-specific occupancy and audit statements</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print / Save as PDF
        </button>
    </div>
</div>

<!-- Report Category Tabs -->
<ul class="nav nav-pills mb-3 d-print-none">
    <li class="nav-item">
        <a class="nav-link {{ $type === 'daily' || empty($type) ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
            <i class="bi bi-file-earmark-text me-1"></i> Overview Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type === 'bookings' ? 'active' : '' }}" href="{{ route('admin.reports.bookings') }}">
            <i class="bi bi-ticket-detailed me-1"></i> Booking Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type === 'revenue' ? 'active' : '' }}" href="{{ route('admin.reports.revenue') }}">
            <i class="bi bi-cash-stack me-1"></i> Revenue Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type === 'occupancy' ? 'active' : '' }}" href="{{ route('admin.reports.occupancy') }}">
            <i class="bi bi-pie-chart me-1"></i> Occupancy Reports
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $type === 'parking' ? 'active' : '' }}" href="{{ route('admin.reports.parking') }}">
            <i class="bi bi-car-front me-1"></i> Parking Reports
        </a>
    </li>
</ul>

<!-- Report Filters -->
<div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.reports') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Start Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">End Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Parking Facility</label>
                <select name="location_id" class="form-select form-select-sm">
                    <option value="">-- All Facilities --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Generate Report
                </button>
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Printable Header -->
<div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
    <h2 class="fw-bold">SMARTPARK AI &bull; Operational Statement</h2>
    <p class="text-muted mb-0">Reporting Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    <small class="text-muted">Generated on {{ now()->format('Y-m-d H:i:s') }}</small>
</div>

<!-- Summary Metrics Bar -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted fw-bold">TOTAL RESERVATIONS</small>
            <h3 class="fw-bold mb-0 text-primary">{{ $summary['total_bookings'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted fw-bold">COMPLETED EXITS</small>
            <h3 class="fw-bold mb-0 text-success">{{ $summary['completed'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted fw-bold">ACTIVE IN BAY</small>
            <h3 class="fw-bold mb-0 text-danger">{{ $summary['active'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm p-3 text-center">
            <small class="text-muted fw-bold">CANCELLED</small>
            <h3 class="fw-bold mb-0 text-secondary">{{ $summary['cancelled'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-md">
        <div class="card border-0 shadow-sm p-3 text-center bg-light">
            <small class="text-muted fw-bold">NET REVENUE</small>
            <h3 class="fw-bold mb-0 text-success">${{ number_format($summary['total_revenue'], 2) }}</h3>
        </div>
    </div>
</div>

<!-- Detailed Data Records Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Facility & Slot</th>
                    <th>Duration</th>
                    <th>Total Fee</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr>
                        <td class="small">{{ \Carbon\Carbon::parse($rec->booking_date)->format('M d, Y') }}</td>
                        <td class="font-monospace fw-bold text-primary">#{{ $rec->booking_id }}</td>
                        <td>{{ $rec->user->name ?? 'Guest' }}</td>
                        <td class="font-monospace">{{ $rec->vehicle->vehicle_number ?? '-' }}</td>
                        <td>
                            <div>{{ $rec->parkingLocation->name ?? '-' }}</div>
                            <small class="badge bg-light text-dark border">{{ $rec->slot->slot_number ?? '-' }}</small>
                        </td>
                        <td>{{ $rec->total_hours }}h</td>
                        <td class="fw-bold text-success">${{ number_format($rec->total_price, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($rec->booking_status == 'COMPLETED') bg-success 
                                @elseif($rec->booking_status == 'ACTIVE') bg-danger 
                                @elseif($rec->booking_status == 'CONFIRMED') bg-primary 
                                @else bg-secondary @endif">
                                {{ $rec->booking_status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x display-5 d-block mb-3"></i>
                            No operational records found for selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="card-footer bg-white border-0 py-3 d-print-none">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
