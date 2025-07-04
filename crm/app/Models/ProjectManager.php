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
        'department_id',
        'image',
    ];

    protected $hidden = [
        'password'
    ];

    function department()
    {
        return $this->belongsTo(Department::class,);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
