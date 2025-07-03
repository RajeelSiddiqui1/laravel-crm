<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'project_manager' => [
            'driver' => 'session',
            'provider' => 'project_managers',
        ],

        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],

        'team_lead' => [
            'driver' => 'session',
            'provider' => 'team_leads',
        ],

        'project_owner' => [
            'driver' => 'session',
            'provider' => 'project_owners',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'project_managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\ProjectManager::class,
        ],

        'team_leads' => [
            'driver' => 'eloquent',
            'model' => App\Models\TeamLead::class,
        ],

        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],

        'project_owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\ProjectOwner::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
