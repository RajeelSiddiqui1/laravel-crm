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
    ];
    protected $casts = [
        'department_ids' => 'array',
    ];
    
    protected $hidden = ['password'];

  public function department()
{
    return $this->belongsTo(Department::class, 'department_id');
}

    
    
    

}
