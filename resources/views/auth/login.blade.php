<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMARTPARK AI</title>
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
            max-width: 450px;
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
            <p class="text-muted small mt-1">Customer Account Login</p>
        </div>

        @include('components.alert')

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email / Mobile Number -->
            <div class="mb-3">
                <label for="login" class="form-label fw-semibold">Email / Mobile Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login', 'user@smartpark.com') }}" required autofocus placeholder="name@example.com or 9876543210">
                </div>
                @error('login')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label fw-semibold mb-0">Password</label>
                    <span class="small text-muted">Forgot Password</span>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="password" required placeholder="Enter password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" checked>
                <label class="form-check-label small text-muted" for="remember">Remember Me</label>
            </div>

            <!-- Buttons: LOGIN and REGISTER -->
            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-primary py-2 fw-semibold">LOGIN</button>
                <a href="{{ route('register') }}" class="btn btn-outline-primary py-2 fw-semibold">REGISTER</a>
            </div>

            <!-- Demo Credentials Helper -->
            <div class="p-3 bg-light rounded border text-muted small">
                <strong class="d-block text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Demo User Login:</strong>
                <div>Email: <code>user@smartpark.com</code></div>
                <div>Password: <code>password</code></div>
            </div>
        </form>
    </div>
</body>
</html>
