<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellCenterPos extends Model
{

    protected $table = 'cell_center_pos';
   

    protected $casts = [
        'comment' => 'array',
        'status' => 'array',
        'attachments' => 'array',
        'name' => 'array',
        'business_name' => 'array',
        'business_number' => 'array',
        'personal_number' => 'array',
        'personal_email' => 'array',
        'business_email' => 'array',
        'address' => 'array',
        'provider' => 'array',
        'category_pos' => 'array',
        'pos_type' => 'array',
        'debt' => 'array',
        'credit' => 'array',
        'rental' => 'array',
        'business_type' => 'array',
        'date' => 'array',
        'time' => 'array',
    ];

    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    public function employeeSubtask()
    {
        return $this->hasOne(EmployeeSubtask::class);
    }
}