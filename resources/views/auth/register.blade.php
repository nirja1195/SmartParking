<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SMARTPARK AI</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #2563EB;
            --dark-blue: #172554;
            --light-blue: #EFF6FF;
            --bg-main: #F8FAFC;
        }
        body {
            background-color: var(--bg-main);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 520px;
            padding: 2.5rem;
        }
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2 mb-2" style="width: 44px; height: 44px;">
                    <i class="bi bi-p-square-fill fs-4"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">SMARTPARK AI</h4>
            </a>
            <p class="text-muted small mt-1">Create your customer account</p>
        </div>

        @include('components.alert')

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-2 mb-3">
                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Mobile Number -->
                <div class="col-md-6">
                    <label for="phone" class="form-label fw-semibold">Mobile Number</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="9876543210">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label fw-semibold">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Street Address, City, State">{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-2 mb-4">
                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimum 6 chars">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">REGISTER ACCOUNT</button>

            <div class="text-center">
                <span class="text-muted small">Already have an account?</span>
                <a href="{{ route('login') }}" class="small fw-semibold text-primary text-decoration-none ms-1">LOGIN</a>
            </div>
        </form>
    </div>
</body>
</html>
