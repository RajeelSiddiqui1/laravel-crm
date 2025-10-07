<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $recipient;
    public $status;

    public function __construct($task, $recipient, $status)
    {
        $this->task = $task;
        $this->recipient = $recipient;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Task Status Updated')
                    ->markdown('emails.manager.task_status_updated');
    }
}
