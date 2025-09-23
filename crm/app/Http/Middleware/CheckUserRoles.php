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
            Auth::guard('project_owner')->check() ||   // Project Owner
            Auth::guard('project_manager')->check() || // Project Manager
            Auth::guard('employee')->check() ||        // Employee
            Auth::guard('visitor')->check()            // Visitor
        ) {
            return $next($request);
        }

        // ✅ Agar login nahi hai
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // ✅ Har role ke liye alag login page
        if ($request->is('team-lead/*')) {
            return redirect()->route('team_lead.login');
        } elseif ($request->is('project-owner/*')) {
            return redirect()->route('project_owner.login');
        } elseif ($request->is('project-manager/*')) {
            return redirect()->route('project_manager.login');
        } elseif ($request->is('employee/*')) {
            return redirect()->route('employee.login');
        } elseif ($request->is('visitor/*')) {
            return redirect()->route('visitor.login');
        }

        // ✅ Default fallback (Admin/web)
        return redirect()->route('login');
    }
}
