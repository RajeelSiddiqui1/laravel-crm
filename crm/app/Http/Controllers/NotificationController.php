<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function allNotifications()
    {
        $user = null;
        $guard = null;

        // Detect authenticated user and guard
        foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
            if (Auth::guard($g)->check()) {
                $user = Auth::guard($g)->user();
                $guard = $g;
                break;
            }
        }

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Fetch notifications for the authenticated user only
        $notifications = Notification::where('user_id', $user->id)
            ->where('user_type', $guard)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.all', compact('notifications', 'guard'));
    }

    public function showAllForAdmin()
{
    if (!Auth::guard('admin')->check()) {
        abort(403, 'Admins only.');
    }

    $notifications = Notification::latest()->get();
    return view('notifications.admin', compact('notifications'));
}

}
