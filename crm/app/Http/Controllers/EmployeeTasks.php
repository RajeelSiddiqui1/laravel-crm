<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class EmployeeTasks extends Controller
{
    public function employee_task_view($subtaskId)
    {
        $subtask = Subtask::with(['employeeSubtask', 'task'])->findOrFail($subtaskId);
        $employeeSubtask = $subtask->employeeSubtask;

        $leadCount = (int) $subtask->lead ?? 1;
        $leadValues = range(1, $leadCount);

        // Check if task_type is 'cell_center_pos'
        $isCallCenterPos = $subtask->task_type === 'cell_center_pos';
        $isCallCenterAccount = $subtask->task_type === 'cell_center_accounts';

        return view('employee.subtasks_update', compact('subtask', 'employeeSubtask', 'leadValues', 'isCallCenterPos','isCallCenterAccount'));
    }
}
