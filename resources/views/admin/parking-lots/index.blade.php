@extends('layouts.admin')

@section('title', 'Manage Parking Lots')
@section('page-title', 'Parking Lots & Garages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Parking Facilities</h4>
        <p class="text-muted small mb-0">Overview of configured locations, spot distribution, and hourly tariffs</p>
    </div>
    <a href="{{ route('admin.parking-lots.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Facility
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Facility</th>
                    <th>Code</th>
                    <th>Location</th>
                    <th>Capacity & Occupancy</th>
                    <th>Base Rate</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lots as $lot)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $lot->name }}</span>
                        </td>
                        <td>
                            <code>{{ $lot->code }}</code>
                        </td>
                        <td>
                            <div>{{ $lot->address }}</div>
                            <small class="text-muted">{{ $lot->city }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">
                                {{ $lot->available_spots_count ?? 0 }} free / {{ $lot->total_spots }} total
                            </div>
                            <div class="progress mt-1" style="height: 5px; width: 120px;">
                                @php
                                    $occupied = max(0, $lot->total_spots - ($lot->available_spots_count ?? 0));
                                    $pct = $lot->total_spots > 0 ? round(($occupied / $lot->total_spots) * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                        <td>
                            ${{ number_format($lot->hourly_rate, 2) }}/hr
                        </td>
                        <td>
                            @if($lot->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.parking-lots.show', $lot->id) }}" class="btn btn-sm btn-light border" title="View Spots">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.parking-lots.edit', $lot->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.parking-lots.destroy', $lot->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this parking facility?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No parking facilities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
