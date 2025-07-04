<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TeamLead extends Authenticatable
{
    use Notifiable;

    protected $table = 'team_leads';
      protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'department_id',
    ];

    protected $hidden = [
        'password'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
