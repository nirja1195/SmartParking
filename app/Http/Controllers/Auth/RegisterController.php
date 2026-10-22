<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $customerRole = Role::where('slug', Role::CUSTOMER)->first();

        $user = User::create([
            'role_id' => $customerRole ? $customerRole->id : 2,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Welcome to SmartPark! Your account was registered successfully.');
    }
}
