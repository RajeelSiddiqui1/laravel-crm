<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Or just Model if not an authenticatable user
use Illuminate\Notifications\Notifiable;
class Employee extends Authenticatable // Or extends Model
{
    use HasFactory;
      use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'department_id',
        'password', // if employees log in
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * An Employee belongs to one Department.
     */
   public function department()
{
    return $this->belongsTo(Department::class);
}


    /**
     * An Employee can be assigned to many Owner Tasks.
     */
    public function onwerTasks()
    {
        return $this->belongsToMany(OnwerTask::class, 'owner_task_employee', 'employee_id', 'owner_task_id');
    }
}