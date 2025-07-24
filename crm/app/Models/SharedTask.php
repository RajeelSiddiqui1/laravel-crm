<?php

// app/Models/SharedTask.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'assigned_by',
        'assigned_to',
        'owner_task_id',
    ];

    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'owner_task_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(ProjectManager::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(ProjectManager::class, 'assigned_to');
    }
}
