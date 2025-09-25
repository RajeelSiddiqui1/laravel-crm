<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedTask extends Model
{
    protected $fillable = [
        'manager_id',
        'assigend_manager_id',
        'assigned_employee_id',
        'teamlead_id',
        'employee_id',
        'visitor_id',
        'subtask_id',
        'operation_manager_id',
        'operation_teamlead_id',
        'operation_employee_id',
        'cell_center_pos_id',
        'cell_center_account_id',
        'client_details_id',
        'comment',
        'attachments',
        'status',
        'vendor_status',
        'machine_status',
    ];


    public function manager()
    {
        return $this->belongsTo(ProjectManager::class, 'manager_id');
    }

    public function teamlead()
    {
        return $this->belongsTo(TeamLead::class, 'teamlead_id');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    // SharedTask.php (model)
    public function cellCenterPos()
    {
        return $this->belongsTo(CellCenterPos::class, 'cell_center_pos_id', 'id');
    }



    public function cellCenterAccount()
    {
        return $this->belongsTo(CellCenterAccount::class, 'cell_center_account_id', 'id');
    }
    public function clientDetail()
    {
        return $this->belongsTo(ClientDetail::class, 'client_details_id', 'id');
    }
}
