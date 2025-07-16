<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    
    protected $fillable = ['title', 'message', 'user_id', 'user_type', 'is_read'];

}
