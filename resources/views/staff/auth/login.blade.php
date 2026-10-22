<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Terminal Login - SMARTPARK AI</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #2563EB;
            --dark-blue: #172554;
            --bg-main: #1e293b;
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
        .staff-card {
            background: #ffffff;
            border-radius: 1rem;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="staff-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-3 p-2 mb-2" style="width: 48px; height: 48px;">
                <i class="bi bi-person-badge-fill fs-3"></i>
            </div>
            <h4 class="fw-bold text-dark mb-0">SMARTPARK AI</h4>
            <span class="badge bg-dark text-uppercase px-2 py-1 mt-1" style="letter-spacing: 1px; font-size: 0.7rem;">Staff Terminal</span>
        </div>

        @include('components.alert')

        <form method="POST" action="{{ route('staff.login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Staff Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', 'staff@smartpark.com') }}" required autofocus placeholder="staff@smartpark.com">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="password" required placeholder="Enter password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold mb-3">STAFF TERMINAL LOGIN</button>

            <div class="p-3 bg-light rounded border text-muted small">
                <strong class="d-block text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Staff Demo Credentials:</strong>
                <div>Entry Staff: <code>staff@smartpark.com</code> | <code>password</code></div>
                <div>Exit Staff: <code>exitstaff@smartpark.com</code> | <code>password</code></div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Return to Main Home
                </a>
            </div>
        </form>
    </div>
</body>
</html>
