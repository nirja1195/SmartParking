@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page-title', 'Admin Profile & Security')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <!-- Profile Summary Card -->
        <div class="card border-0 shadow-sm text-center p-4 mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
            </div>
            <h4 class="fw-bold mb-1">{{ $admin->name }}</h4>
            <div class="text-muted mb-2">{{ $admin->email }}</div>
            <div>
                <span class="badge bg-primary-subtle text-primary px-3 py-1 fs-6">{{ $admin->role ?? 'Super Admin' }}</span>
                <span class="badge bg-success-subtle text-success px-3 py-1 fs-6 ms-1">{{ $admin->status ?? 'Active' }}</span>
            </div>
            <hr class="my-3">
            <div class="text-start small text-muted">
                <div class="d-flex justify-content-between py-1">
                    <span>Admin ID:</span>
                    <strong class="text-dark">#{{ $admin->id }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Member Since:</span>
                    <strong class="text-dark">{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Last Updated:</span>
                    <strong class="text-dark">{{ $admin->updated_at ? $admin->updated_at->diffForHumans() : 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Update Account Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i> Account Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="admin-username" class="form-label fw-semibold">Username</label>
                        <input id="admin-username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $admin->username) }}" maxlength="80" pattern="[a-z0-9_.-]+" autocomplete="username">
                        <div class="form-text">Optional login username. Use lowercase letters, numbers, dots, underscores or hyphens.</div>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <input type="text" class="form-control bg-light" value="{{ $admin->role ?? 'Super Admin' }}" readonly disabled>
                            <div class="form-text">Role changes require system console intervention.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <input type="text" class="form-control bg-light" value="{{ $admin->status ?? 'Active' }}" readonly disabled>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card border-0 shadow-sm" id="password">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-warning"></i> Security & Change Password</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="admin-username" class="form-label fw-semibold">Username</label>
                        <input id="admin-username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $admin->username) }}" maxlength="80" pattern="[a-z0-9_.-]+" autocomplete="username">
                        <div class="form-text">Optional login username. Use lowercase letters, numbers, dots, underscores or hyphens.</div>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-key me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
