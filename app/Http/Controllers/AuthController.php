<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Requirement #8 & #14: Main Landing Page.
     * Dynamic CMS, locations, services, and authentication modal.
     */
    public function landing(): View
    {
        $services = \App\Models\ParkingService::active()->get();
        $locations = \App\Models\ParkingLocation::where('status', 'Active')
            ->with(['zones.slots'])
            ->get();

        return view('welcome', compact('services', 'locations'));
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Check whether login identifier is an email or a phone number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Invalid credentials provided. Please check your details and try again.'],
            ]);
        }

        if ($user->status === 'blocked') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Your account has been blocked. Please contact support.'], 403);
            }
            return back()->with('error', 'Your account has been blocked. Please contact support.');
        }

        if ($user->status !== 'active') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Your account is currently inactive.'], 403);
            }
            return back()->with('error', 'Your account is inactive.');
        }

        Auth::guard('web')->login($user, $remember);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            $intendedAction = $request->input('intended_action');
            $redirectUrl = match($intendedAction) {
                'find-parking' => route('user.find-parking'),
                'dashboard' => route('user.dashboard'),
                default => session()->pull('url.intended', route('user.find-parking')),
            };

            return response()->json([
                'success' => true,
                'message' => 'Welcome back, ' . $user->name . '!',
                'redirect_url' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        }

        return redirect()->intended(route('user.find-parking'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address', 'Not Specified'),
            'password' => Hash::make($request->input('password')),
            'status' => 'active',
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            $intendedAction = $request->input('intended_action');
            $redirectUrl = match($intendedAction) {
                'find-parking' => route('user.find-parking'),
                default => route('user.dashboard'),
            };

            return response()->json([
                'success' => true,
                'message' => 'Account created and logged in successfully.',
                'redirect_url' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        }

        return redirect()->route('user.find-parking')
            ->with('success', 'Account created successfully.');
    }

    public function forgotPassword(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No customer account found with that email address.',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset instructions have been sent to your email.',
            ]);
        }

        return back()->with('status', 'Password reset instructions have been sent to your email.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been safely logged out.');
    }
}
