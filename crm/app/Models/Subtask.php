<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'cell_center_pos_ids' => 'array',
        'cell_center_account_ids' => 'array',
    ];

    public function task()
    {
        return $this->belongsTo(OnwerTask::class, 'owner_task_id');
    }


    public function employeeSubtask()
    {
        if ($this->task_type === 'cell_center_pos') {
            return $this->hasOne(CellCenterPos::class, 'id')->where('employee_id', Auth::guard('employee'));
        } elseif ($this->task_type === 'cell_center_accounts') {
            return $this->hasOne(CellCenterAccount::class, 'id')->where('employee_id', Auth::guard('employee'));
        }
        return $this->hasOne(EmployeeSubtask::class); // Adjust to your original model
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }

   

    public function cellCenterPos()
    {
        return $this->belongsTo(CellCenterPos::class, 'cell_center_pos_id');
    }
}