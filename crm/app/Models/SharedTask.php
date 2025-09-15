<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedTask extends Model
{
    protected $fillable = [
        'manager_id',
        'employee_id',
        'visitor_id',
        'subtask_id',
        'cell_center_pos_id',
        'cell_center_account_id',
        'comment',
        'status',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

   
    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

     public function pos()
    {
        return $this->belongsTo(CellCenterPos::class, 'cell_center_pos_id');
    }

    public function account()
    {
        return $this->belongsTo(CellCenterAccount::class, 'cell_center_account_id');
    }
}
