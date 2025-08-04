<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellCenterPos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment',
        'name',
        'business_name',
        'business_number',
        'personal_number',
        'personal_email',
        'business_email',
        'address',
        'provider',
        'category_pos',
        'pos_type',
        'debut',
        'credit',
        'rental',
        'business_type',
        'date',
        'time',
        'status',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'comment' => 'array',
        'name' => 'array',
        'business_name' => 'array',
        'business_number' => 'array',
        'personal_number' => 'array',
        'personal_email' => 'array',
        'business_email' => 'array',
        'address' => 'array',
        'provider' => 'array',
        'category_pos' => 'array',
        'pos_type' => 'array',
        'debut' => 'array',
        'credit' => 'array',
        'rental' => 'array',
        'business_type' => 'array',
        'date' => 'array',
        'time' => 'array',
        'status' => 'array',
        'attachments' => 'array',
    ];

}