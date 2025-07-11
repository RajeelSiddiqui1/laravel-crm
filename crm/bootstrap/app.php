<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserRoles;
use App\Http\Middleware\ShareProjectManagerNotification;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.roles' => CheckUserRoles::class,
        ]);
        $middleware->append(ShareProjectManagerNotification::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
