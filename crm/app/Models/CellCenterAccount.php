<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CellCenterAccount extends Model
{
    protected $fillable = [
        'employee_id',
        'driving_license',
        'email',
        'phone',
        'bussiness_number',
        'corpuration_number',
        'corpuration_email',
        'corpuration_documents',
        'pervious_history',
        'fees',
        'status',
        'comments',
        'attachments',
    ];

   
    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}