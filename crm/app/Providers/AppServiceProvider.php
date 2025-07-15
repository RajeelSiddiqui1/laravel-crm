<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


public function boot(): void
{
    View::composer('*', function ($view) {
        $user = null;
        $guard = null;

        foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
            if (Auth::guard($g)->check()) {
                $user = Auth::guard($g)->user();
                $guard = $g;
                break;
            }
        }

        $notificationCount = 0;

        if ($user) {
            $notificationCount = Notification::where('user_id', $user->id)
                ->where('user_type', $guard)
                ->count();
        }

        $view->with('notificationCount', $notificationCount);
    });
}

}
