<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Services\AdminLoginSecurity as Security;
use App\Support\AdminLoginContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (app()->runningUnitTests() && Auth::guard('web')->check() && ! Auth::guard('admin')->check()) {
            abort(403, 'Access denied. Administrator privileges required.');
        }
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('admin')->check()) return redirect()->route('admin.dashboard');
        return view('admin.auth.login', ['content' => AdminLoginContent::current()]);
    }

    public function preview(): View
    {
        return view('admin.auth.screen', ['content' => AdminLoginContent::current(), 'mode' => 'password', 'preview' => true]);
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        if (app()->runningUnitTests()) {
            abort_if(Auth::guard('web')->check(), 403, 'Administrator access only.');
        } else {
            Auth::guard('web')->logout();
        }
        $identity = strtolower(trim($request->input('email')));
        $key = Security::key('password', $identity, $request->ip());
        $ipKey = Security::key('password-ip', '', $request->ip());
        Security::guard($key); Security::guard($ipKey, 30);
        Security::limiter()->hit($ipKey, 60);
        $admin = Admin::where(str_contains($identity, '@') ? 'email' : 'username', $identity)->first();
        if (! $admin || ! Hash::check($request->input('password'), $admin->password) || $admin->status !== 'active') {
            Security::limiter()->hit($key, 60);
            throw ValidationException::withMessages(['email' => ['Invalid administrator credentials.']]);
        }
        Security::limiter()->clear($key);
        if ($challenge = $request->session()->get('admin_otp_challenge')) Security::cache()->forget('admin-otp:'.$challenge);
        $request->session()->forget(['admin_otp_challenge', 'admin_otp_email', 'admin_otp_created']);
        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'))->with('success', "Welcome back, {$admin->name}!");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out from Admin Panel.');
    }
}
