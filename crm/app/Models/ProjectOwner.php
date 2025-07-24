<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProjectOwner extends Authenticatable
{
    use Notifiable;

    protected $table = 'project_owners';

    protected $fillable = [
        'name',
        'email',
        'password',
        'project_manager_id', // Added assuming this is needed for the relationship
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the project manager associated with the project owner.
     */
    public function projectManager()
    {
        return $this->belongsTo(ProjectManager::class, 'project_manager_id');
    }
}