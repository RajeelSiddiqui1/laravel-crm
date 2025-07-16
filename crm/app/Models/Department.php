<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectManager;
use App\Models\TeamLead;

class Department extends Model
{
    protected $fillable = ['name'];

    public function projectManagers()
    {
        return $this->belongsToMany(ProjectManager::class, 'department_project_manager', 'department_id', 'project_manager_id');
    }

    public function teamLeads()
    {
        return $this->hasMany(TeamLead::class, 'department_id');
    }
}
