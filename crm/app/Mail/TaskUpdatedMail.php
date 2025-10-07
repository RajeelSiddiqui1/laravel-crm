<?php

namespace App\Mail;

use App\Models\OnwerTask;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $account;
    public $task;

    public function __construct($account, OnwerTask $task)
    {
        $this->account = $account;
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('Task Updated Notification')
            ->view('emails.task_updated')
            ->with([
                'clientName' => $this->account->clientname ?? 'N/A',
                'status' => $this->task->status,
                'department' => $this->task->department->name ?? 'N/A',
                'priority' => $this->account->priority ?? 'N/A',
            ]);
    }
}

