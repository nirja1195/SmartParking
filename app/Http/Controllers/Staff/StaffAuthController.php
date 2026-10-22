<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffLoginRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffAuthController extends Controller
{
    public function showLoginForm(): View
    {
        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }

        return view('staff.auth.login');
    }

    public function login(StaffLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        $staff = Staff::where('email', $credentials['email'])->first();

        if (! $staff || ! Hash::check($credentials['password'], $staff->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid staff credentials.'],
            ]);
        }

        if ($staff->status !== 'active') {
            return back()->with('error', 'Staff account is deactivated.');
        }

        Auth::guard('staff')->login($staff);
        $request->session()->regenerate();

        return redirect()->intended(route('staff.dashboard'))
            ->with('success', "Welcome, {$staff->name} ({$staff->role})!");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')->with('success', 'Logged out from Staff Terminal.');
    }
}
