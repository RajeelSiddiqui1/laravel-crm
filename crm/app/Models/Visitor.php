<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'department_ids'
    ];

     protected $casts = [
        'department_ids' => 'array'
    ];
}
