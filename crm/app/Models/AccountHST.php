<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHST extends Model
{
    protected $table = "accounts_hst";
    protected $fillable = [
        'clientname',
        'phone',
        'email',
        'due_date',
        'corpration_number',
        'corpration_name',
        'nature_of_business',
        'priority',
        'department_id',
        'team_lead_id',
        'attachments',
    ];


    // In AccountT1, AccountT2, AccountHST models
    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'task_id');
    }
}
