<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedTask extends Model
{
    protected $fillable = [
        'owner_task_id',
        'department_id',
        'assigned_by',
        'assigned_to',
    ];

    public function task()
    {
        return $this->belongsTo(OnwerTask::class, 'owner_task_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(ProjectManager::class, 'assigned_by');
    }

    public function sharedTo()
    {
        return $this->belongsTo(ProjectManager::class, 'assigned_to');
    }
}
