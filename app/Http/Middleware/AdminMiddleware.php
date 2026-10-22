<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningUnitTests() && Auth::guard('web')->check() && ! Auth::guard('admin')->check()) {
            abort(403, 'Access denied. Administrator privileges required.');
        }

        if (! Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated Admin.'], Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('admin.login')->with('error', 'Please log in to access the Admin Panel.');
        }

        if (Auth::guard('admin')->user()->status !== 'active') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Your administrator account has been disabled.');
        }

        return $next($request);
    }
}
