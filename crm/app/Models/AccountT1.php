<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountT1 extends Model
{
    protected $table = "accounts_t1";
    protected $fillable = [
        'clientname',
        'period',
        'driving_license',
        'sim_number',
        'business_name',
        'family_name',
        'year',
        'team_lead_id',
        'manager_status',
        'team_status',
    ];

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
