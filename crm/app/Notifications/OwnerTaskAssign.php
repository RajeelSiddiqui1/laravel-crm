<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\OnwerTask;

class OwnerTaskAssign extends Notification
{
    public $task;

    public function __construct(OnwerTask $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Task Assigned')
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new task "' . $this->task->client_name . '" has been assigned to you.')
            ->action('View Task', url('/project_manager/tasks/' . $this->task->id))
            ->line('Thank you.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Task Assigned',
            'message' => 'A new task "' . $this->task->client_name . '" has been assigned to you.',
            'task_id' => $this->task->id,
        ];
    }
}
