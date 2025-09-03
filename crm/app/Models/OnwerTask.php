<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnwerTask extends Model
{
    protected $table = "owner_tasks";

    protected $fillable = [
        'name',
        'client_name',
        'audio_file',
        'managers',
        'account_t1_id',
        'account_t2_id',
        'account_hst_id',
        'manager_operation_id',
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

    public function accountT2()
    {
        return $this->belongsTo(AccountT2::class, 'account_t2_id');
    }

    public function accountT1()
    {
        return $this->belongsTo(AccountT1::class, 'account_t1_id');
    }

    public function accountHst()
    {
        return $this->belongsTo(AccountHST::class, 'account_hst_id'); // ✅ new relation
    }
    public function managerOperation()
    {
        return $this->belongsTo(ManagerOperation::class, 'manager_operation_id');
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

    public function sharedTasks()
    {
        return $this->hasMany(SharedTask::class, 'owner_task_id');
    }
}
