@extends('layouts.user')

@section('title', 'My Profile')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <div class="card-body">
                <div class="avatar-circle mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle fs-1 fw-bold" style="width: 90px; height: 90px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-2"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>
                <span class="badge bg-success-subtle text-success border border-success px-3 py-1">Active Account</span>

                <hr class="my-4">

                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fw-bold fs-5 text-primary">{{ $totalBookings }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Bookings</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fw-bold fs-5 text-warning">{{ $activeBookings }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Active</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fw-bold fs-5 text-info">{{ $totalVehicles }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Vehicles</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-gear me-2 text-primary"></i> Edit Profile Information</h5>
            </div>
            <div class="card-body p-4">
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

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Account Created</label>
                            <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y h:i A') }}" disabled readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Residential / Business Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter your full street address...">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
