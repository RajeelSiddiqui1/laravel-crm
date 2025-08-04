<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = [
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
        'attachments',
        'attachment_type',
        'department_id',
        'lead',
        'form_task',
        'cell_center_pos_id',
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

    public function employeeSubtask()
    {
        return $this->hasOne(EmployeeSubtask::class, 'subtask_id');
    }

    public function cellCenterPos()
    {
        return $this->belongsTo(CellCenterPos::class, 'cell_center_pos_id');
    }
}