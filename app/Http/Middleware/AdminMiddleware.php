<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // Get the authenticated user

        // Check if user exists and if the user has the role of 'admin'
        if (!$user || !$user->role) {
            // Unauthorized response if user is not admin
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Continue with the request if the user is an admin
        return $next($request);
    }
}
