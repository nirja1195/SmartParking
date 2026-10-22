@extends('layouts.admin')

@section('title', 'Vehicles Management')
@section('page-title', 'Fleet & Vehicle Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Registered Vehicles</h4>
        <p class="text-muted mb-0">Manage customer vehicles, license plates, brands, and vehicle classifications.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        <i class="bi bi-plus-circle me-1"></i> Register Vehicle
    </button>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.vehicles.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search license plate, brand, model, customer name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">All Vehicle Types</option>
                    @foreach(['Car', 'Bike', 'Auto', 'Bus', 'Truck', 'EV'] as $vt)
                        <option value="{{ $vt }}" {{ request('type') === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Vehicles Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>License Plate</th>
                    <th>Customer / Owner</th>
                    <th>Type</th>
                    <th>Brand & Model</th>
                    <th>Color</th>
                    <th>Bookings</th>
                    <th>Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td>
                            <span class="badge bg-dark font-monospace fs-6 px-3 py-2 border border-secondary text-warning">
                                <i class="bi bi-car-front me-1"></i> {{ $vehicle->vehicle_number }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $vehicle->user->name ?? 'Unknown Customer' }}</div>
                            <small class="text-muted">{{ $vehicle->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge 
                                @if($vehicle->vehicle_type == 'Car') bg-primary 
                                @elseif($vehicle->vehicle_type == 'Bike') bg-info text-dark 
                                @elseif($vehicle->vehicle_type == 'EV') bg-success 
                                @else bg-secondary @endif">
                                {{ $vehicle->vehicle_type }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $vehicle->brand ?? '-' }}</div>
                            <small class="text-muted">{{ $vehicle->model ?? '-' }}</small>
                        </td>
                        <td>
                            @if($vehicle->color)
                                <span class="badge bg-light text-dark border">{{ $vehicle->color }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">{{ $vehicle->bookings_count ?? 0 }} trips</span>
                        </td>
                        <td class="text-muted small">
                            {{ $vehicle->created_at ? $vehicle->created_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editVehicleModal{{ $vehicle->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteVehicleModal{{ $vehicle->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editVehicleModal{{ $vehicle->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.vehicles.update', $vehicle->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Vehicle: {{ $vehicle->vehicle_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">License Plate Number <span class="text-danger">*</span></label>
                                            <input type="text" name="vehicle_number" class="form-control text-uppercase" value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Vehicle Type <span class="text-danger">*</span></label>
                                            <select name="vehicle_type" class="form-select" required>
                                                @foreach(['Car', 'Bike', 'Auto', 'Bus', 'Truck', 'EV'] as $vt)
                                                    <option value="{{ $vt }}" {{ $vehicle->vehicle_type === $vt ? 'selected' : '' }}>{{ $vt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Brand / Make</label>
                                                <input type="text" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" placeholder="e.g. Toyota">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Model</label>
                                                <input type="text" name="model" class="form-control" value="{{ old('model', $vehicle->model) }}" placeholder="e.g. Camry">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Color</label>
                                            <input type="text" name="color" class="form-control" value="{{ old('color', $vehicle->color) }}" placeholder="e.g. Silver">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteVehicleModal{{ $vehicle->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold text-danger">Delete Vehicle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to permanently delete vehicle <strong>{{ $vehicle->vehicle_number }}</strong> owned by <strong>{{ $vehicle->user->name ?? 'Customer' }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No vehicles found matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vehicles->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $vehicles->links() }}
        </div>
    @endif
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.vehicles.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Register New Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Customer / Owner <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">License Plate Number <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_number" class="form-control text-uppercase" placeholder="e.g. MH01AB1234 or NY-7890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Vehicle Type <span class="text-danger">*</span></label>
                        <select name="vehicle_type" class="form-select" required>
                            @foreach(['Car', 'Bike', 'Auto', 'Bus', 'Truck', 'EV'] as $vt)
                                <option value="{{ $vt }}">{{ $vt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Brand / Make</label>
                            <input type="text" name="brand" class="form-control" placeholder="e.g. Honda">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Model</label>
                            <input type="text" name="model" class="form-control" placeholder="e.g. Civic">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Color</label>
                        <input type="text" name="color" class="form-control" placeholder="e.g. Pearl White">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Register Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
