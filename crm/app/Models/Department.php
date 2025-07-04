<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{


    public function teamLeads()
    {
        return $this->hasMany(TeamLead::class);
    }

     public function projectManagers()
    {
        return $this->belongsToMany(ProjectManager::class, 'department_project_manager', 'department_id', 'project_manager_id');
    }
}
