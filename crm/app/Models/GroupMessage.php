<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $table = "group_messages";

    protected $fillable = [
    'owner_task_id',
    'content',
    'attachments',
    'sender_id',
    'sender_type',
    'receiver_ids',
];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'sender_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(TeamLead::class, 'sender_id');
    }

    public function projectManager()
    {
        return $this->belongsTo(ProjectManager::class, 'sender_id');
    }

    public function projectOwner()
    {
        return $this->belongsTo(ProjectOwner::class, 'sender_id');
    }
}
