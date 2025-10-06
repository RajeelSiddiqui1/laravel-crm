<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $deletedBy;

    public function __construct($task, $deletedBy)
    {
        $this->task = $task;
        $this->deletedBy = $deletedBy;
    }

    public function build()
    {
        return $this->subject('Task Deleted Notification')
                    ->view('emails.manager_task_deleted')
                    ->with([
                        'task' => $this->task,
                        'deletedBy' => $this->deletedBy,
                    ]);
    }
}
