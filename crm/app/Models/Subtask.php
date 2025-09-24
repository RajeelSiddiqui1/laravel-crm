<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subtask extends Model
{
    protected $fillable = [
        'owner_task_id',
        'team_lead_id',
        'employee_id',
        'title',
        'description',
        'attachments',
        'lead',
        'task_type',
        'teamlead_status',
        'employee_status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'account_t1_id',
        'account_t2_id',
        'account_hst_id',
        'manager_operation_id',
        'cell_center_account_ids',
        'call_center_pos_ids',
        'client_detail_ids'
    ];

    protected $casts = [
        'call_center_pos_ids' => 'array',
        'cell_center_account_ids' => 'array',
        'client_detail_ids' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(TeamLead::class, 'team_lead_id');
    }


    public function employeeSubtask()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    public function cellCenterPos()
    {
        return $this->belongsTo(CellCenterPos::class, 'task_id');
    }


    public function cellCenterPosAccount()
    {
        return $this->belongsTo(CellCenterAccount::class, 'task_id');
    }
    
    public function clientDetails()
    {
        return $this->belongsTo(ClientDetail::class, 'task_id');
    }
}
