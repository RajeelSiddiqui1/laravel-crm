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
        'corporation_number',
        'corporation_name',
        'nature_of_business',
        'priority',
        'department_id',
        'team_lead_id',
        'manager_status',
        'team_status',
        'attachments',
    ];


    // In AccountT1, AccountT2, AccountHST models
    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'task_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function teamLead()
    {
        return $this->belongsTo(Employee::class, 'team_lead_id');
    }
}
