@extends('layouts.user')

@section('title', 'Account Settings')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i> Change Password</h5>
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

                <form action="{{ route('user.settings.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        <small class="text-muted">Minimum 6 characters with mixed characters recommended</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-key me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-sliders me-2 text-primary"></i> Notification Preferences</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small">Configure which notifications you want to receive for parking events.</p>

                <div class="list-group list-group-flush mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <div>
                            <h6 class="mb-1 fw-bold">Booking Confirmations</h6>
                            <p class="text-muted small mb-0">Receive instant alerts when slot is successfully reserved</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked id="pref1">
                        </div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <div>
                            <h6 class="mb-1 fw-bold">Gate Entry & Exit Alerts</h6>
                            <p class="text-muted small mb-0">Get notified immediately when your vehicle enters or exits a parking bay</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked id="pref2">
                        </div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <div>
                            <h6 class="mb-1 fw-bold">Overtime Warning Alerts</h6>
                            <p class="text-muted small mb-0">Receive SMS / App alerts 15 minutes before booking time expires</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked id="pref3">
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 small">
                    <i class="bi bi-info-circle-fill me-2"></i> All critical transactional receipts and gate security logs are stored permanently on your account for verification.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
