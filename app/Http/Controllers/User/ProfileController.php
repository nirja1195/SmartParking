<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Requirement #37: User Profile (/profile).
     */
    public function profile(Request $request): View
    {
        $user = $request->user();
        $totalBookings = $user->bookings()->count();
        $totalVehicles = $user->vehicles()->count();
        $activeBookings = $user->bookings()->where('booking_status', 'ACTIVE')->count();

        return view('user.profile', compact('user', 'totalBookings', 'totalVehicles', 'activeBookings'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Requirement #45: User Settings (/settings).
     */
    public function settings(Request $request): View
    {
        return view('user.settings', ['user' => $request->user()]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
