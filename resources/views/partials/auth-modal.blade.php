<!-- SmartParking Authentication Modal (Login / Register / Forgot Password) -->
<div id="authModalBackdrop" class="auth-modal-backdrop" onclick="handleBackdropClick(event)">
    <div class="auth-modal-card" id="authModalCard" onclick="event.stopPropagation()">
        <!-- Close Button (X) -->
        <button type="button" class="auth-modal-close-btn" onclick="closeAuthModal()" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>

        <!-- Dynamic Header (Logo + Titles) -->
        <div class="text-center mb-4">
            <div class="auth-modal-logo-badge mb-3">
                <span class="auth-modal-p">P</span>
            </div>
            <h3 class="auth-modal-title fw-extrabold text-dark mb-1" id="authModalTitle">Welcome to SmartParking</h3>
            <p class="auth-modal-subtitle text-secondary small mb-0" id="authModalSubtitle">Login to continue parking</p>
        </div>

        <!-- Inline Error / Status Alert Banner -->
        <div id="authAlertBox" class="alert alert-danger d-none py-2 px-3 small rounded-3 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-1"></i> <span id="authAlertMessage"></span>
        </div>

        <!-- Hidden Input to track Intended Action ('dashboard' or 'find-parking') -->
        <input type="hidden" id="authIntendedAction" value="find-parking">

        <!-- 1. LOGIN VIEW -->
        <div id="authViewLogin" class="auth-view-content">
            <form id="loginModalForm" onsubmit="handleModalLogin(event)">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark mb-1">Email / Mobile Number</label>
                    <div class="auth-input-group">
                        <span class="auth-input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" name="login" id="loginInput" class="form-control auth-input-control" placeholder="Enter email or mobile" required autofocus>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold small text-dark mb-1">Password</label>
                    <div class="auth-input-group">
                        <span class="auth-input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="passwordInput" class="form-control auth-input-control" placeholder="Enter password" required>
                        <button type="button" class="auth-pwd-toggle" onclick="togglePasswordVisibility('passwordInput', this)" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                        <label class="form-check-label small text-secondary" for="rememberMe">
                            Remember Me
                        </label>
                    </div>
                    <a href="javascript:void(0)" onclick="switchAuthView('forgot')" class="auth-sub-link small fw-semibold">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" id="loginSubmitBtn" class="btn btn-auth-primary w-100 py-2.5 fw-bold mb-3">
                    <span class="btn-text">LOGIN</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>

                <div class="text-center pt-2 border-top">
                    <span class="small text-secondary">Don't have an account?</span>
                    <a href="javascript:void(0)" onclick="switchAuthView('register')" class="auth-switch-link fw-bold ms-1">
                        REGISTER
                    </a>
                </div>
            </form>
        </div>

        <!-- 2. REGISTER VIEW -->
        <div id="authViewRegister" class="auth-view-content d-none">
            <form id="registerModalForm" onsubmit="handleModalRegister(event)">
                @csrf
                <div class="mb-2">
                    <label class="form-label fw-bold small text-dark mb-1">Full Name</label>
                    <div class="auth-input-group">
                        <span class="auth-input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="regNameInput" class="form-control auth-input-control" placeholder="Enter your full name" required>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark mb-1">Email Address</label>
                        <div class="auth-input-group">
                            <span class="auth-input-icon"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="regEmailInput" class="form-control auth-input-control" placeholder="name@email.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark mb-1">Mobile Number</label>
                        <div class="auth-input-group">
                            <span class="auth-input-icon"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" id="regPhoneInput" class="form-control auth-input-control" placeholder="Mobile number" required>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark mb-1">Password</label>
                        <div class="auth-input-group">
                            <span class="auth-input-icon"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="regPasswordInput" class="form-control auth-input-control" placeholder="Password (min 6)" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark mb-1">Confirm Password</label>
                        <div class="auth-input-group">
                            <span class="auth-input-icon"><i class="bi bi-shield-check"></i></span>
                            <input type="password" name="password_confirmation" id="regConfirmPasswordInput" class="form-control auth-input-control" placeholder="Re-enter password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" id="registerSubmitBtn" class="btn btn-auth-primary w-100 py-2.5 fw-bold mb-3">
                    <span class="btn-text">CREATE ACCOUNT</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>

                <div class="text-center pt-2 border-top">
                    <span class="small text-secondary">Already have an account?</span>
                    <a href="javascript:void(0)" onclick="switchAuthView('login')" class="auth-switch-link fw-bold ms-1">
                        LOGIN
                    </a>
                </div>
            </form>
        </div>

        <!-- 3. FORGOT PASSWORD VIEW -->
        <div id="authViewForgot" class="auth-view-content d-none">
            <form id="forgotModalForm" onsubmit="handleModalForgot(event)">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark mb-1">Registered Email Address</label>
                    <div class="auth-input-group">
                        <span class="auth-input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="forgotEmailInput" class="form-control auth-input-control" placeholder="Enter your account email" required>
                    </div>
                    <small class="text-muted">We will send password reset instructions to this email.</small>
                </div>

                <button type="submit" id="forgotSubmitBtn" class="btn btn-auth-primary w-100 py-2.5 fw-bold mb-3">
                    <span class="btn-text">SEND RESET LINK</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>

                <div class="text-center pt-2 border-top">
                    <a href="javascript:void(0)" onclick="switchAuthView('login')" class="auth-sub-link small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Styles (SmartParking Theme) -->
<style>
/* Modal Backdrop Overlay */
.auth-modal-backdrop {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(16, 24, 40, 0.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s ease;
}

.auth-modal-backdrop.is-open {
    opacity: 1;
    visibility: visible;
}

/* Modal Card */
.auth-modal-card {
    background: #FFFFFF;
    border-radius: 22px;
    box-shadow: 0 25px 50px -12px rgba(16, 24, 40, 0.35), 0 0 0 1px rgba(16, 24, 40, 0.05);
    width: 100%;
    max-width: 460px;
    padding: 2.25rem 2.25rem 2rem;
    position: relative;
    transform: scale(0.92) translateY(12px);
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 90vh;
    overflow-y: auto;
}

.auth-modal-backdrop.is-open .auth-modal-card {
    transform: scale(1) translateY(0);
}

/* Close Button (X) */
.auth-modal-close-btn {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #F1F5F9;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 10;
}

.auth-modal-close-btn:hover {
    background: #E2E8F0;
    color: #0F172A;
    transform: rotate(90deg);
}

/* Logo Badge */
.auth-modal-logo-badge {
    width: 46px;
    height: 46px;
    background: #101828;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(16, 24, 40, 0.2);
}

.auth-modal-p {
    color: #FFC72C;
    font-weight: 900;
    font-size: 1.6rem;
    line-height: 1;
}

.auth-modal-title {
    font-size: 1.45rem;
    letter-spacing: -0.02em;
}

/* Input Fields with Icons */
.auth-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.auth-input-icon {
    position: absolute;
    left: 1rem;
    color: #94A3B8;
    font-size: 1.05rem;
    pointer-events: none;
    z-index: 2;
}

.auth-input-control {
    padding-left: 2.75rem !important;
    padding-right: 2.75rem !important;
    height: 46px;
    border-radius: 12px;
    border: 1.5px solid #E2E8F0;
    font-size: 0.95rem;
    color: #0F172A;
    background-color: #F8FAFC;
    transition: all 0.2s ease;
}

.auth-input-control:focus {
    background-color: #FFFFFF;
    border-color: #FFC72C;
    box-shadow: 0 0 0 4px rgba(255, 199, 44, 0.22);
    outline: none;
}

.auth-pwd-toggle {
    position: absolute;
    right: 0.85rem;
    background: transparent;
    border: none;
    color: #94A3B8;
    font-size: 1.1rem;
    cursor: pointer;
    padding: 0.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
    z-index: 2;
}

.auth-pwd-toggle:hover {
    color: #0F172A;
}

/* Primary Button (SmartParking Yellow) */
.btn-auth-primary {
    background-color: #FFC72C;
    color: #101828;
    border: none;
    border-radius: 12px;
    font-weight: 800;
    font-size: 1rem;
    letter-spacing: 0.03em;
    box-shadow: 0 4px 14px rgba(255, 199, 44, 0.45);
    transition: all 0.25s ease;
}

.btn-auth-primary:hover,
.btn-auth-primary:focus {
    background-color: #E5A800;
    color: #000000;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(255, 199, 44, 0.55);
}

.btn-auth-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Links */
.auth-sub-link {
    color: #64748B;
    text-decoration: none;
    transition: color 0.2s;
}

.auth-sub-link:hover {
    color: #0F172A;
    text-decoration: underline;
}

.auth-switch-link {
    color: #101828;
    text-decoration: none;
    border-bottom: 2px solid #FFC72C;
    padding-bottom: 1px;
    transition: all 0.2s;
}

.auth-switch-link:hover {
    color: #E5A800;
    border-bottom-color: #101828;
}

/* Mobile Responsive */
@media (max-width: 576px) {
    .auth-modal-card {
        padding: 1.75rem 1.25rem 1.5rem;
        border-radius: 18px;
    }
}
</style>

<!-- Modal JavaScript Logic -->
<script>
let currentAuthView = 'login';

/**
 * Open the Authentication Modal
 * @param {'login'|'register'|'forgot'} view
 * @param {'dashboard'|'find-parking'} intendedAction
 */
function openAuthModal(view = 'login', intendedAction = 'find-parking') {
    document.getElementById('authIntendedAction').value = intendedAction;
    switchAuthView(view);
    hideAuthAlert();

    const backdrop = document.getElementById('authModalBackdrop');
    backdrop.classList.add('is-open');
    document.body.style.overflow = 'hidden'; // prevent scrolling background

    setTimeout(() => {
        if (view === 'login') {
            document.getElementById('loginInput')?.focus();
        } else if (view === 'register') {
            document.getElementById('regNameInput')?.focus();
        } else if (view === 'forgot') {
            document.getElementById('forgotEmailInput')?.focus();
        }
    }, 150);
}

/**
 * Close the Authentication Modal
 */
function closeAuthModal() {
    const backdrop = document.getElementById('authModalBackdrop');
    backdrop.classList.remove('is-open');
    document.body.style.overflow = '';
    hideAuthAlert();
}

/**
 * Handle clicking outside the modal dialog (on the backdrop)
 */
function handleBackdropClick(event) {
    if (event.target === document.getElementById('authModalBackdrop')) {
        closeAuthModal();
    }
}

/**
 * Switch views inside the modal (login / register / forgot)
 */
function switchAuthView(view) {
    currentAuthView = view;
    hideAuthAlert();

    const title = document.getElementById('authModalTitle');
    const subtitle = document.getElementById('authModalSubtitle');
    const viewLogin = document.getElementById('authViewLogin');
    const viewRegister = document.getElementById('authViewRegister');
    const viewForgot = document.getElementById('authViewForgot');

    viewLogin.classList.add('d-none');
    viewRegister.classList.add('d-none');
    viewForgot.classList.add('d-none');

    if (view === 'login') {
        title.textContent = 'Welcome to SmartParking';
        subtitle.textContent = 'Login to continue parking';
        viewLogin.classList.remove('d-none');
    } else if (view === 'register') {
        title.textContent = 'Create Customer Account';
        subtitle.textContent = 'Register to search & book spaces instantly';
        viewRegister.classList.remove('d-none');
    } else if (view === 'forgot') {
        title.textContent = 'Reset Password';
        subtitle.textContent = 'Enter your email for reset instructions';
        viewForgot.classList.remove('d-none');
    }
}

/**
 * Show error or status alert banner in modal
 */
function showAuthAlert(message, type = 'danger') {
    const alertBox = document.getElementById('authAlertBox');
    const alertMessage = document.getElementById('authAlertMessage');
    alertBox.className = `alert alert-${type} py-2 px-3 small rounded-3 mb-3`;
    alertMessage.textContent = message;
    alertBox.classList.remove('d-none');
}

/**
 * Hide alert banner
 */
function hideAuthAlert() {
    const alertBox = document.getElementById('authAlertBox');
    alertBox.classList.add('d-none');
}

/**
 * Toggle password reveal
 */
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

/**
 * Handle Login Submission via AJAX
 */
async function handleModalLogin(event) {
    event.preventDefault();
    hideAuthAlert();

    const form = document.getElementById('loginModalForm');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    const intendedAction = document.getElementById('authIntendedAction').value;

    const formData = new FormData(form);
    formData.append('intended_action', intendedAction);

    submitBtn.disabled = true;
    btnText.textContent = 'LOGGING IN...';
    spinner.classList.remove('d-none');

    try {
        const response = await fetch('{{ route("login") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showAuthAlert(data.message || 'Success! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url || '{{ route("user.find-parking") }}';
            }, 600);
        } else {
            let errorMsg = data.message || 'Invalid credentials provided.';
            if (data.errors) {
                const firstKey = Object.keys(data.errors)[0];
                errorMsg = data.errors[firstKey][0];
            }
            showAuthAlert(errorMsg, 'danger');
            submitBtn.disabled = false;
            btnText.textContent = 'LOGIN';
            spinner.classList.add('d-none');
        }
    } catch (err) {
        showAuthAlert('Unable to reach server. Please check your connection.', 'danger');
        submitBtn.disabled = false;
        btnText.textContent = 'LOGIN';
        spinner.classList.add('d-none');
    }
}

/**
 * Handle Register Submission via AJAX
 */
async function handleModalRegister(event) {
    event.preventDefault();
    hideAuthAlert();

    const form = document.getElementById('registerModalForm');
    const submitBtn = document.getElementById('registerSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    const intendedAction = document.getElementById('authIntendedAction').value;

    const formData = new FormData(form);
    formData.append('intended_action', intendedAction);

    submitBtn.disabled = true;
    btnText.textContent = 'CREATING...';
    spinner.classList.remove('d-none');

    try {
        const response = await fetch('{{ route("register") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showAuthAlert(data.message || 'Account created successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url || '{{ route("user.find-parking") }}';
            }, 600);
        } else {
            let errorMsg = data.message || 'Registration failed.';
            if (data.errors) {
                const firstKey = Object.keys(data.errors)[0];
                errorMsg = data.errors[firstKey][0];
            }
            showAuthAlert(errorMsg, 'danger');
            submitBtn.disabled = false;
            btnText.textContent = 'CREATE ACCOUNT';
            spinner.classList.add('d-none');
        }
    } catch (err) {
        showAuthAlert('Server error during registration. Please try again.', 'danger');
        submitBtn.disabled = false;
        btnText.textContent = 'CREATE ACCOUNT';
        spinner.classList.add('d-none');
    }
}

/**
 * Handle Forgot Password Submission via AJAX
 */
async function handleModalForgot(event) {
    event.preventDefault();
    hideAuthAlert();

    const form = document.getElementById('forgotModalForm');
    const submitBtn = document.getElementById('forgotSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    const formData = new FormData(form);

    submitBtn.disabled = true;
    btnText.textContent = 'SENDING...';
    spinner.classList.remove('d-none');

    try {
        const response = await fetch('{{ route("password.email") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showAuthAlert(data.message || 'Reset link sent to your email.', 'success');
            submitBtn.disabled = false;
            btnText.textContent = 'SEND RESET LINK';
            spinner.classList.add('d-none');
        } else {
            let errorMsg = data.message || 'Could not find that email address.';
            if (data.errors && data.errors.email) {
                errorMsg = data.errors.email[0];
            }
            showAuthAlert(errorMsg, 'danger');
            submitBtn.disabled = false;
            btnText.textContent = 'SEND RESET LINK';
            spinner.classList.add('d-none');
        }
    } catch (err) {
        showAuthAlert('Failed to submit reset request. Please try again.', 'danger');
        submitBtn.disabled = false;
        btnText.textContent = 'SEND RESET LINK';
        spinner.classList.add('d-none');
    }
}

// Close modal on Escape key press
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const backdrop = document.getElementById('authModalBackdrop');
        if (backdrop && backdrop.classList.contains('is-open')) {
            closeAuthModal();
        }
    }
});

// Auto-open modal if requested via URL parameter (?login=1 or ?register=1)
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('login') || urlParams.has('modal')) {
        openAuthModal('login', urlParams.get('intended') || 'find-parking');
    } else if (urlParams.has('register')) {
        openAuthModal('register', urlParams.get('intended') || 'find-parking');
    }
});
</script>
