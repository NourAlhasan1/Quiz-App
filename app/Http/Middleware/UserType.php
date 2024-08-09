<?php
// UserType.php

namespace App\Http\Middleware;

use Closure;

class UserType
{
    public function handle($request, Closure $next, $role)
    {
        // Fetch the authenticated user
        $user = auth()->user();

        if (($role === 'student' && $user->role !== 1) || ($role === 'normal' && $user->role !== 0)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
