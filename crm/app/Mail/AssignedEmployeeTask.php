<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignedEmployeeTask extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $employee;

    public function __construct($task, $employee)
    {
        $this->task = $task;
        $this->employee = $employee;
    }

    public function build()
    {
        return $this->subject('New Task Assigned: ' . $this->task->name)
                    ->markdown('emails.assigned_employee_task');
    }
}
