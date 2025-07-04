<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $table = "employees";

    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'department_id',
        'image',
    ];

    protected $hidden = [
        'password',
    ];

    // ✅ Use belongsTo because employees have one department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
