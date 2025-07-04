<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRoles
{
    public function handle($request, Closure $next)
    {
        if (
            Auth::guard('web')->check() ||             // Admin
            Auth::guard('team_lead')->check() ||       // Team Lead
            Auth::guard('project_owner')->check() ||
            Auth::guard('project_manager')->check() ||
            Auth::guard('employee')     // Project Owner
        ) {
            return $next($request);
        }

        // Prevent redirect to login — show 403 or JSON
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        abort(403, 'Access denied');
    }
}
