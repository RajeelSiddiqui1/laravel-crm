<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{protected $fillable = [
    'title',
    'description',
    'assigned_employee_id',
    'owner_task_id',
    'start_date',
    'start_time',
    'end_date',
    'end_time',
    'comment',
    'status',
    'attachment',
    'attachment_type',
];


protected $casts = [
    'attachments' => 'array',
];


    public function task()
    {
        return $this->belongsTo(OnwerTask::class, 'owner_task_id');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }
}
