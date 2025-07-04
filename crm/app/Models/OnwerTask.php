<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnwerTask extends Model
{

    protected $table = "owner_tasks";
    protected $fillable = [
        'client_name',
        'description',
        'client_email',
        'client_contact',
        'department_id',
        'project_manager_id',
        'manager_email',
        'priority',
        'status',
    ];

    protected $casts = [
    'start_date' => 'datetime',
    'deadline' => 'datetime',
];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(ProjectManager::class, 'project_manager_id');
    }
}
