<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProjectManager extends Authenticatable
{
    use Notifiable;

    protected $table = "project_managers";
    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'department_ids',
        'image',
        'manager_id',
    ];
    protected $casts = [
        'department_ids' => 'array',
    ];

    protected $hidden = ['password'];

  public function departments()
{
    return $this->belongsToMany(
        Department::class,
        'department_manager', // pivot table
        'manager_id',         // FK on pivot
        'department_id'       // FK to departments
    );
}



    public function teamleads()
    {
        return $this->hasMany(TeamLead::class, 'manager_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
}



