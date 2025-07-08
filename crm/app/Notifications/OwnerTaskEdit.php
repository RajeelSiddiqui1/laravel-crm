<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\OnwerTask;

class OwnerTaskEdit extends Notification
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
            ->subject('Edit this Task')
            ->greeting('Hello ' . $notifiable->name)
            ->line( $this->task->client_name . '" task has been updatedto you.')
            ->action('View Task', url('/project_manager/tasks/' . $this->task->id))
            ->line('Thank you.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Edit this Task',
            'message' => $this->task->client_name . " task has been updatedto you",
            'task_id' => $this->task->id,
        ];
    }
}
