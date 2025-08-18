<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CellCenterPos extends Model
{
    protected $fillable = [
        'employee_id',
        'subtask_id',
        'comment',
        'name',
        'business_name',
        'business_number',
        'personal_number',
        'personal_email',
        'business_email',
        'address',
        'provider',
        'category_pos',
        'pos_type',
        'debt',
        'credit',
        'rental',
        'business_type',
        'date',
        'time',
        'status',
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