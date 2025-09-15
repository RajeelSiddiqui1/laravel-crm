<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // <-- yeh import karo
use Illuminate\Notifications\Notifiable;

class Visitor extends Authenticatable
{
    use Notifiable;

    protected $table = 'visitors'; // agar table ka naam custom hai

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'department_ids',
        'status',
    ];

     protected $casts = [
        'department_ids' => 'array',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];


}
