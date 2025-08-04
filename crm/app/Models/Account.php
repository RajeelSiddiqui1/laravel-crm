<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientname',
        'phone',
        'email',
        'due_date',
        'nature_of_business',
        'priority',
        'department_id',
        'team_lead_id',
        'attachments',
    ];
}