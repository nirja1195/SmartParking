<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('login');
        }

        if ($request->user()->role?->slug !== Role::CUSTOMER && $request->user()->role?->slug !== Role::ADMIN) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden: Customer account required.'], Response::HTTP_FORBIDDEN);
            }

            abort(Response::HTTP_FORBIDDEN, 'Access restricted to customer accounts.');
        }

        return $next($request);
    }
}
