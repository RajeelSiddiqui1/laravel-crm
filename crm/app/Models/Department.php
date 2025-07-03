<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function projectManagers()
    {
        return $this->belongsToMany(ProjectManager::class);
    }

    public function teamLeads()
{
    return $this->hasMany(TeamLead::class);
}

}
