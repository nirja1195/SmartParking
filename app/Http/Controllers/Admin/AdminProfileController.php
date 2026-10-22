<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'username' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('admins')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $admin->update($validated);

        return back()->with('success', 'Admin profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin = Auth::guard('admin')->user();
        $admin->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Admin security password changed successfully.');
    }
}
