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

    // ✅ Mark unread notifications as read
    Notification::where('user_id', $user->id)
        ->where('user_type', $guard)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    // ✅ Fetch all notifications (read + unread)
    $notifications = Notification::where('user_id', $user->id)
        ->where('user_type', $guard)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('notifications.all', compact('notifications', 'guard'));
}

public function showAllForAdmin()
{
    if (!Auth::guard('project_owner')->check()) {
        abort(403, 'Admins only.');
    }

    $admin = Auth::guard('project_owner')->user();

    Notification::where('user_id', $admin->id)
        ->where('user_type', 'project_owner')
        ->where('is_read', false)
        ->update(['is_read' => true]);

    $notifications = Notification::latest()->get();
    return view('notifications.admin', compact('notifications'));
}


}
