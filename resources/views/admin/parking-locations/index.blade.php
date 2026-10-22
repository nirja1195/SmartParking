@extends('layouts.admin')

@section('title', 'Parking Locations')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small text-muted mb-1">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Parking Locations</li>
        </ol>
    </nav>

    <!-- 5. PAGE HEADING -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-extrabold text-dark mb-1" style="font-size: 2rem; letter-spacing: -0.02em;">
                Parking <span class="text-yellow">Locations</span>
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.92rem;">Manage all parking locations. View, edit or add new locations.</p>
        </div>

        <a href="{{ route('admin.locations.create') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 border-0 shadow-sm rounded-3 d-inline-flex align-items-center gap-1">
            <i class="bi bi-plus fs-5"></i> Add New Location
        </a>
    </div>

    <!-- 6 & 7. FOUR DYNAMIC STATISTICS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Locations -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Total Locations</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['total_locations']) }}</h3>
                    <div class="small">
                        <span class="text-success fw-bold">&bull; {{ $stats['active_locations'] }} Active</span>
                        <span class="text-secondary ms-2">&bull; {{ $stats['inactive_locations'] }} Inactive</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Slots -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3" style="background-color: #FEF0C7;">
                    <i class="bi bi-car-front-fill text-dark"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Total Slots</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['total_slots']) }}</h3>
                    <div class="text-muted small">Across all locations</div>
                </div>
            </div>
        </div>

        <!-- Card 3: Available Slots -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3" style="background-color: #DCFCE7;">
                    <i class="bi bi-car-front-fill text-success"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Available Slots</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['available_slots']) }}</h3>
                    <div class="text-muted small">Updated in real-time</div>
                </div>
            </div>
        </div>

        <!-- Card 4: Occupied Slots -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 h-100 d-flex flex-row align-items-center">
                <div class="yellow-icon-badge me-3" style="background-color: #FEE2E2;">
                    <i class="bi bi-car-front-fill text-danger"></i>
                </div>
                <div>
                    <div class="text-secondary fw-medium small mb-1">Occupied Slots</div>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.6rem;">{{ number_format($stats['occupied_slots']) }}</h3>
                    <div class="text-muted small">Updated in real-time</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. SEARCH AND FILTER BAR -->
    <div class="card-custom p-3 mb-4">
        <form action="{{ route('admin.locations.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Search Input -->
            <div class="col-12 col-md-5 position-relative">
                <i class="bi bi-search text-muted position-absolute start-0 top-50 translate-middle-y ms-3"></i>
                <input type="text" name="search" class="form-control ps-5" style="border-radius: 8px;" placeholder="Search by location name or address..." value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-12 col-sm-6 col-md-3">
                <select name="status" class="form-select" style="border-radius: 8px;">
                    <option value="All Status" {{ request('status') === 'All Status' || !request('status') ? 'selected' : '' }}>All Status</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Location Type Filter -->
            <div class="col-12 col-sm-6 col-md-3">
                <select name="location_type" class="form-select" style="border-radius: 8px;">
                    <option value="All Location Types" {{ request('location_type') === 'All Location Types' || !request('location_type') ? 'selected' : '' }}>All Location Types</option>
                    <option value="Commercial" {{ request('location_type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="Airport" {{ request('location_type') === 'Airport' ? 'selected' : '' }}>Airport</option>
                    <option value="Office" {{ request('location_type') === 'Office' ? 'selected' : '' }}>Office</option>
                    <option value="Transit" {{ request('location_type') === 'Transit' ? 'selected' : '' }}>Transit</option>
                    <option value="Hospital" {{ request('location_type') === 'Hospital' ? 'selected' : '' }}>Hospital</option>
                    <option value="Educational" {{ request('location_type') === 'Educational' ? 'selected' : '' }}>Educational</option>
                    <option value="Public" {{ request('location_type') === 'Public' ? 'selected' : '' }}>Public</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-12 col-md-1 text-md-end d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-dark btn-sm rounded-2 px-3">Filter</button>
                <a href="{{ route('admin.locations.index') }}" class="btn btn-link text-secondary text-decoration-none btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- 9. PARKING LOCATIONS TABLE -->
    <div class="card-custom mb-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead style="background-color: #F8FAFC; font-size: 0.78rem; text-transform: uppercase; color: #64748B;">
                    <tr>
                        <th class="ps-4 py-3 border-0" style="width: 40px;">#</th>
                        <th class="py-3 border-0" style="min-width: 220px;">Location Name</th>
                        <th class="py-3 border-0" style="min-width: 180px;">Address</th>
                        <th class="py-3 border-0 text-center">Total Slots</th>
                        <th class="py-3 border-0 text-center">Available</th>
                        <th class="py-3 border-0 text-center">Occupied</th>
                        <th class="py-3 border-0">Location Type</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="pe-4 py-3 border-0 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $loc)
                        <tr>
                            <td class="ps-4 py-3 text-secondary fw-semibold">
                                {{ ($locations->currentPage() - 1) * $locations->perPage() + $index + 1 }}
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $loc->image_url }}" alt="{{ $loc->name }}" class="rounded-2 me-3" style="width: 60px; height: 42px; object-fit: cover; border: 1px solid #E2E8F0;">
                                    <div>
                                        <a href="{{ route('admin.locations.show', $loc->id) }}" class="fw-bold text-dark text-decoration-none hover-warning">
                                            {{ $loc->name }}
                                        </a>
                                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                            <i class="bi bi-geo-alt text-secondary"></i> {{ $loc->area ?: $loc->address }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-secondary small">
                                {{ $loc->address }}
                            </td>
                            <td class="py-3 text-center fw-bold text-dark">
                                {{ number_format($loc->total_slots_count ?? $loc->total_capacity) }}
                            </td>
                            <td class="py-3 text-center">
                                <span class="text-success fw-bold">&bull; {{ number_format($loc->available_slots_count ?? 0) }}</span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="text-danger fw-bold">&bull; {{ number_format($loc->occupied_slots_count ?? 0) }}</span>
                            </td>
                            <td class="py-3">
                                <span class="{{ $loc->type_badge_class }}">
                                    {{ $loc->location_type ?: 'Commercial' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="{{ $loc->status === 'Active' ? 'badge-status-active' : 'badge-status-inactive' }}">
                                    {{ $loc->status }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-center">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.locations.show', $loc->id) }}" class="btn-action-square" title="View Location">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.locations.edit', $loc->id) }}" class="btn-action-square" title="Edit Location">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-action-square btn-action-square-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $loc->id }}" title="Delete Location">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade text-start" id="deleteModal{{ $loc->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-dark">Confirm Location Removal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <p class="mb-2">Are you sure you want to delete parking location <strong>{{ $loc->name }}</strong>?</p>
                                                <p class="text-secondary small mb-0">If this facility has active bookings or slots, deletion will be blocked safely to preserve operational lifecycle.</p>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger fw-bold">Delete Location</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2 text-secondary"></i>
                                No parking locations match your search or filter parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 21. PAGINATION MATCHING SCREENSHOT -->
        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 bg-white">
            <div class="text-secondary small">
                Showing {{ $locations->firstItem() ?? 0 }} to {{ $locations->lastItem() ?? 0 }} of {{ $locations->total() }} locations
            </div>
            <div>
                {{ $locations->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
