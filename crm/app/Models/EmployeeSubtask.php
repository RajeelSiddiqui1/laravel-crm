<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSubtask extends Model
{
    protected $fillable = [ 'subtask_id', 'comments', 'statuses', 'attachments', 'name', 'business_name', 'business_num', 
    'bussiness_type','personal_num', 'personal_email', 'business_email', 'address', 'perivos', 'provider', 'category_pos', 'pos_type', 'debt', 'credit', 'rentle','date','time' ];

   protected $casts = [
    'name' => 'array',
    'business_name' => 'array',
    'business_num' => 'array',
    'personal_num' => 'array',
    'personal_email' => 'array',
    'business_email' => 'array',
    'address' => 'array',
    'perivos' => 'array',
    'provider' => 'array',
    'bussiness_type'=>'array',
    'category_pos' => 'array',
    'pos_type' => 'array',
    'debt' => 'array',
    'credit' => 'array',
    'rentle' => 'array',
    'attachments' => 'array',
    'comments' => 'array',
    'statuses' => 'array',
];



    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }
}
