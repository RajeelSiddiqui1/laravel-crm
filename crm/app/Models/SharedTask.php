<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedTask extends Model
{
    protected $fillable = [
        'manager_id',
        'employee_id',
        'visitor_id ',
        'subtask_id',
        'comment',
        'status'
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
}
