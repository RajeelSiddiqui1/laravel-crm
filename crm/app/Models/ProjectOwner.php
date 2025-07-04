<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProjectOwner extends Authenticatable
{

       use Notifiable;
    protected $table = "project_owners";

      protected $fillable = [
        'name', 'email',
    ];

    protected $hidden = [
        'password'
    ];

    public function projectManager()
{
    return $this->belongsTo(ProjectManager::class, 'project_manager_id');
}

}
