@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('subtitle', 'Enter your registered email address to receive password reset instructions')

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label fw-semibold">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Send Password Reset Link</button>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Sign In
        </a>
    </div>
</form>
@endsection
