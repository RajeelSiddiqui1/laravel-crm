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
        'bussiness_name',
        'famliy_name',
        'year'
    ];

    public function ownerTask()
    {
        return $this->belongsTo(OnwerTask::class, 'task_id');
    }
}
