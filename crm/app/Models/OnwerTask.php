<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnwerTask extends Model
{
    protected $table = "owner_tasks";

    protected $fillable = [
        'name',
        'client_name',
        'description',
        'client_email',
        'client_contact',
        'department_id',
        'project_manager_id',
        'project_manager_task',
        'team_lead_id',
        'employee_id',
        'manager_email',
        'priority',
        'status',
        'start_date',
        'deadline',
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

    public function projectManagerTask()
    {
        return $this->belongsTo(ProjectManager::class, 'project_manger_task');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'owner_task_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(TeamLead::class, 'team_lead_id');
    }
}
