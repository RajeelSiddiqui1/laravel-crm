<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSubtask extends Model
{
    protected $fillable = [
        'subtask_id',
        'employee_id',
        'comments',
        'statuses',
        'attachments',
    ];

    protected $casts = [
        'comments' => 'array',
        'statuses' => 'array',
        'attachments' => 'array',
    ];

    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function cellCenterPos(): BelongsTo
    {
        return $this->belongsTo(CellCenterPos::class, 'subtask_id', 'subtask_id')
            ->where('employee_id', $this->employee_id);
    }

    public function cellCenterAccount(): BelongsTo
    {
        return $this->belongsTo(CellCenterAccount::class, 'subtask_id', 'subtask_id')
            ->where('employee_id', $this->employee_id);
    }
}