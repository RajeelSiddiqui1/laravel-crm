<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSubtask extends Model
{
    protected $fillable = [
        'subtask_id',
        'cell_center_pos_id',
    ];

    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    public function cellCenterPos()
    {
        return $this->belongsTo(CellCenterPos::class);
    }
}