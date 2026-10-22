<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ $content['form']['page_title'] }}</title>
<link rel="stylesheet" href="{{ asset('css/vendor/about-fonts.css') }}"><link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-icons.min.css') }}"><link rel="stylesheet" href="{{ asset('css/admin-login.css') }}"></head>
<body>
<main class="login-page">
    <section class="visual-panel" aria-label="SmartParking administration">
        <img class="visual-background" src="{{ asset($content['hero']['image']) }}" alt="{{ $content['hero']['alt'] }}" fetchpriority="high"><div class="visual-shade" aria-hidden="true"></div>
        <a class="brand brand-light" href="{{ route('home') }}"><img src="{{ asset($content['branding']['logo']) }}" alt=""><span><strong>{{ $content['branding']['name'] }}<em>{{ $content['branding']['highlight'] }}</em></strong><small>{{ $content['branding']['tagline'] }} <b>•</b> {{ $content['branding']['tagline_suffix'] }}</small></span></a>
        <div class="visual-copy"><h1>{{ $content['hero']['title'] }}<span>{{ $content['hero']['highlight'] }}</span></h1><p>{{ $content['hero']['description'] }}</p><div class="gold-line" aria-hidden="true"></div>
        <div class="features">@foreach(['feature_1', 'feature_2', 'feature_3'] as $feature)<div><i class="bi bi-{{ $content[$feature]['icon'] }}" aria-hidden="true"></i><span>{{ $content[$feature]['label'] }}</span></div>@endforeach</div></div>
        <div class="visual-footer">{{ $content['hero']['footer'] }}</div>
    </section>
    <section class="form-panel" aria-labelledby="login-title">
        <div class="top-contact">{{ $content['form']['new_here'] }} <a href="{{ $content['form']['contact_url'] }}">{{ $content['form']['contact_label'] }}</a></div>
        <div class="decor-rings" aria-hidden="true"></div>
        <div class="form-inner">
            <a class="brand brand-center" href="{{ route('home') }}"><img src="{{ asset($content['branding']['logo']) }}" alt=""><span><strong>{{ $content['branding']['name'] }}<em>{{ $content['branding']['highlight'] }}</em></strong><small>{{ $content['branding']['tagline'] }} <b>•</b> {{ $content['branding']['tagline_suffix'] }}</small></span></a>
            <div class="welcome"><h2 id="login-title">{{ $content[$mode === 'otp' ? 'otp' : 'form']['title'] }}</h2><p>{{ $content[$mode === 'otp' ? 'otp' : 'form']['description'] }}</p></div>
            @if(!empty($preview))<p class="notice">Preview only — login controls are disabled while you are signed in.</p>@endif
            @if(session('success'))<div class="notice success" role="status">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="notice error" role="alert">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="notice error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @if($mode === 'password')
            <form method="post" action="{{ route('admin.login') }}" class="login-form">@csrf
                <fieldset @disabled(!empty($preview))>
                <div class="field"><i class="bi bi-envelope" aria-hidden="true"></i><div><label for="email">{{ $content['form']['email_label'] }}</label><input id="email" name="email" type="text" value="{{ old('email') }}" placeholder="{{ $content['form']['email_placeholder'] }}" autocomplete="username" autocapitalize="none" spellcheck="false" required maxlength="255" @error('email') aria-invalid="true" @enderror></div></div>
                <div class="field"><i class="bi bi-lock" aria-hidden="true"></i><div><label for="password">{{ $content['form']['password_label'] }}</label><input id="password" name="password" type="password" placeholder="{{ $content['form']['password_placeholder'] }}" autocomplete="current-password" required maxlength="1024" @error('password') aria-invalid="true" @enderror></div><button class="password-toggle" type="button" aria-label="Show password" aria-controls="password" aria-pressed="false"><i class="bi bi-eye-slash" aria-hidden="true"></i></button></div>
                <div class="options"><label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))><span>{{ $content['form']['remember_label'] }}</span></label><a href="{{ route('admin.otp') }}">{{ $content['form']['forgot_label'] }}</a></div>
                <button class="primary-button" type="submit"><span>{{ $content['form']['submit_label'] }}</span><i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                </fieldset>
                <div class="divider"><span>{{ $content['form']['divider'] }}</span></div><a href="{{ route('admin.otp') }}" class="otp-button"><i class="bi bi-shield-plus" aria-hidden="true"></i>{{ $content['form']['otp_label'] }}</a>
            </form>
            @else
                @if(!$mailReady)<div class="notice" role="status">{{ $content['otp']['unavailable'] }}</div>@endif
                <form method="post" action="{{ route('admin.otp.send') }}" class="login-form otp-form">@csrf
                    <div class="field"><i class="bi bi-envelope" aria-hidden="true"></i><div><label for="email">{{ $content['form']['email_label'] }}</label><input id="email" type="email" name="email" value="{{ old('email', session('admin_otp_email')) }}" autocomplete="username" placeholder="{{ $content['form']['email_placeholder'] }}" required maxlength="255"></div></div>
                    <label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))>{{ $content['form']['remember_label'] }}</label>
                    <button class="primary-button" type="submit" @disabled(!$mailReady)>{{ $content['otp']['send_label'] }}<i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                </form>
                @if(session('admin_otp_challenge'))<form method="post" action="{{ route('admin.otp.verify') }}" class="otp-form">@csrf<div class="field"><i class="bi bi-shield-lock" aria-hidden="true"></i><div><label for="code">{{ $content['otp']['code_label'] }}</label><input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" placeholder="{{ $content['otp']['code_placeholder'] }}" required></div></div><button class="primary-button" type="submit">{{ $content['otp']['verify_label'] }}</button></form>@endif
                <p class="otp-help">{{ $content['otp']['help'] }}</p><a href="{{ route('admin.login') }}" class="back-link">← {{ $content['otp']['back_label'] }}</a>
            @endif
        </div>
        <p class="copyright">{{ $content['form']['copyright'] }}</p><img class="watermark" src="{{ asset($content['branding']['logo']) }}" alt="" aria-hidden="true">
    </section>
</main>
<script>
document.querySelector('.password-toggle')?.addEventListener('click', function () {
    const input = document.getElementById('password'); const show = input.type === 'password';
    input.type = show ? 'text' : 'password'; this.setAttribute('aria-pressed', String(show)); this.setAttribute('aria-label', show ? 'Hide password' : 'Show password'); this.querySelector('i').className = show ? 'bi bi-eye' : 'bi bi-eye-slash';
});
</script>
</body></html>
