@extends('layouts.admin')

@section('title', 'Parking Zones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Parking Zones & Sections</h3>
        <p class="text-muted mb-0">Organize parking facilities into floors, vehicle categories, and slot bays</p>
    </div>
    <a href="{{ route('admin.parking-zones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Zone
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter by Location -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.parking-zones.index') }}" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="col-form-label fw-bold small text-muted">Filter by Facility:</label>
            </div>
            <div class="col-md-4">
                <select name="location_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Parking Locations --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('location_id'))
                <div class="col-auto">
                    <a href="{{ route('admin.parking-zones.index') }}" class="btn btn-sm btn-outline-secondary">Clear Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Zone Identifier</th>
                    <th>Parking Facility</th>
                    <th>Vehicle Type</th>
                    <th>Floor / Level</th>
                    <th>Total Slots</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $z)
                    <tr>
                        <td>
                            <span class="badge bg-primary fs-6">Zone {{ $z->zone_name }}</span>
                            @if($z->section)
                                <small class="text-muted d-block mt-1">Section: {{ $z->section }}</small>
                            @endif
                        </td>
                        <td class="fw-semibold">
                            <a href="{{ route('admin.parking-locations.show', $z->parkingLocation->id) }}" class="text-decoration-none">
                                {{ $z->parkingLocation->name ?? 'Unknown' }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                @if($z->vehicle_type == 'Car') <i class="bi bi-car-front-fill me-1 text-primary"></i>
                                @elseif($z->vehicle_type == 'Bike') <i class="bi bi-bicycle me-1 text-success"></i>
                                @elseif($z->vehicle_type == 'EV') <i class="bi bi-ev-front-fill me-1 text-info"></i>
                                @else <i class="bi bi-truck me-1"></i> @endif
                                {{ $z->vehicle_type }}
                            </span>
                        </td>
                        <td>{{ $z->floor }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary fw-bold fs-6">{{ $z->slots_count }} Slots</span>
                        </td>
                        <td>
                            <span class="badge {{ $z->status === 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $z->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.parking-slots.index') }}?zone_id={{ $z->id }}" class="btn btn-sm btn-outline-primary" title="Manage Slots">
                                    <i class="bi bi-grid-fill"></i> Slots
                                </a>
                                <a href="{{ route('admin.parking-zones.edit', $z->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.parking-zones.destroy', $z->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this zone and all its slots?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-diagram-3 display-5 d-block mb-3"></i>
                            No parking zones found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($zones->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $zones->links() }}
        </div>
    @endif
</div>
@endsection
