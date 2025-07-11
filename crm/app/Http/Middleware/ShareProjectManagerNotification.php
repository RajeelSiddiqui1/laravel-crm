<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareProjectManagerNotification
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('project_manager')->check()) {
            $manager = Auth::guard('project_manager')->user();
            $notificationCount = $manager->unreadNotifications()->count();
            View::share('pmNotificationCount', $notificationCount);
        }

        return $next($request);
    }
}
