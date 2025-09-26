<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerOperation extends Model
{
    protected $fillable = [
        'task_id',
        'project_manager_id',
        'description',
        'attachments',
        'priority',
        'team_lead_id',
        'manager_status',
        'team_status',
    ];

    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'task_id');
    }
}
