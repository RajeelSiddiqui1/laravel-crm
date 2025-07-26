<?php

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use SerializesModels;

    public $notification;

    public function __construct($notification) { $this->notification = $notification; }

    public function broadcastOn()
    {
        return new Channel('notifications.'.$this->notification->user_id.'.'.$this->notification->user_type);
    }

    public function broadcastAs() { return 'NewNotification'; }

    public function broadcastWith()
    {
        return [
            'id'         => $this->notification->id,
            'title'      => $this->notification->title,
            'body'       => $this->notification->body,
            'created_at' => $this->notification->created_at->toDateTimeString(),
        ];
    }
}