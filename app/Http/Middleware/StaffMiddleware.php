<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check()) {
            if (Auth::guard('staff')->user()->status !== 'active') {
                Auth::guard('staff')->logout();
                return redirect()->route('staff.login')->with('error', 'Your staff account is inactive.');
            }
            return $next($request);
        }

        // Allow Admin to access staff operations as well
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->status === 'active') {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated Staff.'], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->route('staff.login')->with('error', 'Please log in with staff credentials.');
    }
}
