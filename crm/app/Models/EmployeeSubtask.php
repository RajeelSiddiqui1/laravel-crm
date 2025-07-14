<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSubtask extends Model
{
    protected $fillable = ['subtask_id', 'comments', 'statuses', 'attachments'];

   protected $casts = [
    'comments' => 'array',
    'statuses' => 'array',
    'attachments' => 'array',
];


    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }
}
