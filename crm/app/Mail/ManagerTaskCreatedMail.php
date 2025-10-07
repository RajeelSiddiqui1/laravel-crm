<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerTaskCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $manager;
    public $teamLead;

    public function __construct($task, $manager, $teamLead)
    {
        $this->task = $task;
        $this->manager = $manager;
        $this->teamLead = $teamLead;
    }

    public function build()
    {
        return $this->subject('🆕 New Task Assigned to You')
                    ->markdown('emails.manager.task_created')
                    ->with([
                        'task' => $this->task,
                        'manager' => $this->manager,
                        'teamLead' => $this->teamLead,
                    ]);
    }
}
