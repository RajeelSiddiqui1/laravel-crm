<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeamLead; // Import the TeamLead model

class OnwerTask extends Model
{
    protected $table = "owner_tasks";
    protected $fillable = [
        'client_name',
        'description',
        'client_email',
        'client_contact',
        'department_id',
        'project_manager_id',
        'team_lead_id',
        'employee_id',
        'manager_email',
        'priority',
        'status',
        'start_date', // Make sure these are in fillable if you're mass assigning them
        'deadline',   // Make sure these are in fillable if you're mass assigning them
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'deadline' => 'datetime',
         'employee_ids' => 'array',
    ];



   public function projectManager()
{
    return $this->belongsTo(ProjectManager::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}

public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

public function subtask()
{
    return $this->belongsTo(Subtask::class, 'subtask_id');
}


public function teamLead()
{
    return $this->belongsTo(TeamLead::class, 'team_lead_id');
}


}