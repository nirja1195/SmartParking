@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gate & Parking Staff Accounts</h3>
        <p class="text-muted mb-0">Create and assign personnel roles: Parking Manager, Entry Staff, and Exit Staff</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStaffModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add Staff Account
    </button>
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

<div class="card border-0 shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Staff Name</th>
                    <th>Email / Login</th>
                    <th>Phone</th>
                    <th>Assigned Role</th>
                    <th>Designated Facility</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffMembers as $s)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $s->name }}</div>
                            <small class="text-muted">ID: #ST-{{ $s->id }}</small>
                        </td>
                        <td>
                            <span class="font-monospace">{{ $s->email }}</span>
                        </td>
                        <td>{{ $s->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge 
                                @if($s->role == 'Parking Manager') bg-primary 
                                @elseif($s->role == 'Entry Staff') bg-success 
                                @elseif($s->role == 'Exit Staff') bg-warning text-dark 
                                @else bg-secondary @endif">
                                {{ $s->role }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $s->parkingLocation->name ?? 'All Facilities' }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.staff.toggle-status', $s->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm badge {{ $s->status === 'active' ? 'bg-success' : 'bg-danger' }} border-0">
                                    {{ ucfirst($s->status) }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.staff.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove staff member {{ $s->name }}?')">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-5 d-block mb-3"></i>
                            No staff accounts configured yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staffMembers->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $staffMembers->links() }}
        </div>
    @endif
</div>

<!-- Modal: Create Staff -->
<div class="modal fade" id="createStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i> Register Staff Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. John Operator" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (Login Username) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. staff1@smartpark.com" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +1 555-0188" value="{{ old('phone') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Assign Staff Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="Parking Manager">Parking Manager (Full Gate & Bay Supervision)</option>
                            <option value="Entry Staff">Entry Staff (Check-in & Gate Inbound Verification)</option>
                            <option value="Exit Staff">Exit Staff (Checkout, Penalty & Gate Outbound)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Assigned Location</label>
                        <select name="parking_location_id" class="form-select">
                            <option value="">-- All Locations (Roaming) --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Create Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
