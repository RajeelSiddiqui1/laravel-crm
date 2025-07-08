<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = [
        'owner_task_id',
        'title',
        'description',
        'assigned_employee_id',
    ];

    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'owner_task_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }
}
