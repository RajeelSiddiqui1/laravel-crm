<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountT2 extends Model
{
    use HasFactory;

    protected $table = "accounts_t2";
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

  
}