@extends('layouts.admin')

@section('title', 'Parking Slots Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Parking Slots & Status Board</h3>
        <p class="text-muted mb-0">Manage individual parking bays, set custom pricing, trigger batch creation, and modify live status</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createSingleSlotModal">
            <i class="bi bi-plus-circle me-1"></i> Add Single Slot
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#batchCreateModal">
            <i class="bi bi-collection-fill me-1"></i> Batch Generate Slots
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.parking-slots.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="location_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Locations --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="zone_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Zones --</option>
                    @foreach($zones as $z)
                        <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>Zone {{ $z->zone_name }} ({{ $z->parkingLocation->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- All Statuses --</option>
                    <option value="AVAILABLE" {{ request('status') == 'AVAILABLE' ? 'selected' : '' }}>AVAILABLE (Green)</option>
                    <option value="RESERVED" {{ request('status') == 'RESERVED' ? 'selected' : '' }}>RESERVED (Blue)</option>
                    <option value="OCCUPIED" {{ request('status') == 'OCCUPIED' ? 'selected' : '' }}>OCCUPIED (Red)</option>
                    <option value="MAINTENANCE" {{ request('status') == 'MAINTENANCE' ? 'selected' : '' }}>MAINTENANCE (Gray)</option>
                    <option value="BLOCKED" {{ request('status') == 'BLOCKED' ? 'selected' : '' }}>BLOCKED (Dark Gray)</option>
                </select>
            </div>

            <div class="col-md-3 text-end">
                <a href="{{ route('admin.parking-slots.index') }}" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
                <a href="{{ route('admin.availability') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-display me-1"></i> Live Grid
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Visual Slot Grid -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i> Live Slot Grid Status</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            @forelse($slots as $slot)
                @php
                    $badgeBg = match($slot->status) {
                        'AVAILABLE' => 'background-color: #10b981; color: white;',
                        'RESERVED' => 'background-color: #2563eb; color: white;',
                        'OCCUPIED' => 'background-color: #ef4444; color: white;',
                        'MAINTENANCE' => 'background-color: #6b7280; color: white;',
                        'BLOCKED' => 'background-color: #1f2937; color: white;',
                        default => 'background-color: #94a3b8; color: white;'
                    };
                @endphp
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 border text-center p-2 shadow-sm position-relative">
                        <div class="p-1 rounded mb-1 fw-bold font-monospace position-relative" style="{{ $badgeBg }}">
                            {{ $slot->slot_number }}
                            @if($slot->price)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle py-0 px-1 border" style="font-size: 0.65rem;" title="Custom rate: ${{ number_format($slot->price, 2) }}/hr">
                                    ${{ number_format($slot->price, 0) }}
                                </span>
                            @endif
                        </div>
                        <div class="small fw-semibold text-truncate mb-1" title="{{ $slot->parkingLocation->name ?? '' }}">
                            {{ $slot->parkingLocation->name ?? 'Loc' }}
                        </div>
                        <div class="text-muted small mb-1">Zone {{ $slot->zone->zone_name ?? '-' }}</div>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                                ${{ number_format($slot->effective_price, 2) }}/hr
                            </span>
                        </div>

                        <!-- Actions: Edit & Dropdown Status Changer -->
                        <div class="d-flex gap-1 mt-auto">
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1" data-bs-toggle="modal" data-bs-target="#editSlotModal{{ $slot->id }}" title="Edit Slot & Price">
                                <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                            </button>
                            <div class="dropdown flex-grow-1">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 font-monospace py-0" style="font-size: 0.75rem;" type="button" data-bs-toggle="dropdown">
                                    {{ $slot->status }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><h6 class="dropdown-header">Change Status</h6></li>
                                    @foreach(['AVAILABLE', 'RESERVED', 'OCCUPIED', 'MAINTENANCE', 'BLOCKED'] as $st)
                                        <li>
                                            <form action="{{ route('admin.parking-slots.update-status', $slot->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $st }}">
                                                <button type="submit" class="dropdown-item small {{ $slot->status == $st ? 'active' : '' }}">
                                                    Mark {{ $st }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.parking-slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Delete slot {{ $slot->slot_number }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger small">
                                                <i class="bi bi-trash me-1"></i> Delete Slot
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Slot Modal -->
                <div class="modal fade" id="editSlotModal{{ $slot->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.parking-slots.update', $slot->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Slot {{ $slot->slot_number }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Slot Identifier / Number <span class="text-danger">*</span></label>
                                        <input type="text" name="slot_number" class="form-control text-uppercase font-monospace" value="{{ old('slot_number', $slot->slot_number) }}" required>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Slot Classification</label>
                                            <select name="slot_type" class="form-select">
                                                @foreach(['Standard', 'VIP', 'Disabled', 'EV Charging', 'Compact', 'Large'] as $stype)
                                                    <option value="{{ $stype }}" {{ old('slot_type', $slot->slot_type) == $stype ? 'selected' : '' }}>{{ $stype }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Custom Hourly Price ($)</label>
                                            <input type="number" step="0.50" min="0" name="price" class="form-control" value="{{ old('price', $slot->price) }}" placeholder="Inherit: ${{ number_format($slot->parkingLocation->parking_charges ?? 0, 2) }}">
                                            <small class="text-muted" style="font-size: 0.7rem;">Leave empty to inherit location rate</small>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Floor</label>
                                            <input type="text" name="floor" class="form-control" value="{{ old('floor', $slot->floor) }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Section</label>
                                            <input type="text" name="section" class="form-control" value="{{ old('section', $slot->section) }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            @foreach(['AVAILABLE', 'RESERVED', 'OCCUPIED', 'MAINTENANCE', 'BLOCKED'] as $st)
                                                <option value="{{ $st }}" {{ old('status', $slot->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Description / Bay Notes</label>
                                        <input type="text" name="description" class="form-control" value="{{ old('description', $slot->description) }}" placeholder="e.g. Near Gate 2, extra wide bay">
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
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-grid-3x3 display-4 d-block mb-3"></i>
                    No parking slots found matching current criteria. Click "Batch Generate Slots" to populate bays.
                </div>
            @endforelse
        </div>
    </div>
    @if($slots->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $slots->links() }}
        </div>
    @endif
</div>

<!-- Modal: Batch Generate Slots -->
<div class="modal fade" id="batchCreateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.parking-slots.batch-store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-collection-fill me-2 text-primary"></i> Batch Generate Parking Slots</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Target Zone <span class="text-danger">*</span></label>
                        <select name="zone_id" class="form-select" required>
                            <option value="">-- Choose Zone --</option>
                            @foreach($zones as $z)
                                <option value="{{ $z->id }}" {{ request('zone_id') == $z->id ? 'selected' : '' }}>
                                    Zone {{ $z->zone_name }} ({{ $z->parkingLocation->name ?? '' }} - {{ $z->vehicle_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label fw-bold">Prefix <span class="text-danger">*</span></label>
                            <input type="text" name="prefix" class="form-control text-uppercase font-monospace" placeholder="e.g. A" value="A" required>
                            <small class="text-muted">e.g. A, B, EV</small>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold">Start # <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="start" class="form-control" value="1" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold">Count <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" name="count" class="form-control" value="10" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded mb-3 small">
                        <strong>Preview:</strong> Slots will be generated as <span class="badge bg-primary font-monospace">A01</span>, <span class="badge bg-primary font-monospace">A02</span> ... <span class="badge bg-primary font-monospace">A10</span>. Existing slots will be skipped to prevent duplicates.
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Floor Override</label>
                            <input type="text" name="floor" class="form-control" placeholder="e.g. Ground Floor">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Section Override</label>
                            <input type="text" name="section" class="form-control" placeholder="e.g. Section 1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-gear-wide-connected me-1"></i> Generate Slots
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Create Single Slot -->
<div class="modal fade" id="createSingleSlotModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.parking-slots.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i> Add Single Parking Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Zone <span class="text-danger">*</span></label>
                        <select name="zone_id" class="form-select" required>
                            <option value="">-- Choose Zone --</option>
                            @foreach($zones as $z)
                                <option value="{{ $z->id }}">
                                    Zone {{ $z->zone_name }} ({{ $z->parkingLocation->name ?? '' }} - {{ $z->vehicle_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Slot Number / Identifier <span class="text-danger">*</span></label>
                        <input type="text" name="slot_number" class="form-control text-uppercase font-monospace" placeholder="e.g. VIP-01, A15, EV-05" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Slot Classification</label>
                            <select name="slot_type" class="form-select">
                                <option value="Standard">Standard</option>
                                <option value="VIP">VIP</option>
                                <option value="Disabled">Disabled / Handicap</option>
                                <option value="EV Charging">EV Charging</option>
                                <option value="Compact">Compact</option>
                                <option value="Large">Large / SUV</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Custom Hourly Price ($)</label>
                            <input type="number" step="0.50" min="0" name="price" class="form-control" placeholder="Inherit location rate">
                            <small class="text-muted" style="font-size: 0.7rem;">Leave empty to inherit location rate</small>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Floor</label>
                            <input type="text" name="floor" class="form-control" placeholder="Inherit zone floor">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Section</label>
                            <input type="text" name="section" class="form-control" placeholder="Inherit zone section">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Initial Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="AVAILABLE" selected>AVAILABLE</option>
                            <option value="RESERVED">RESERVED</option>
                            <option value="OCCUPIED">OCCUPIED</option>
                            <option value="MAINTENANCE">MAINTENANCE</option>
                            <option value="BLOCKED">BLOCKED</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Notes</label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. Near main exit, wide door clearance">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-1"></i> Create Slot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
