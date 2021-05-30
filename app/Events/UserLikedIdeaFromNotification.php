<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Notification as NotificationModel;

class UserLikedIdeaFromNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $email;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NotificationModel $notification, $email = true)
    {
        $this->notification = $notification;
        $this->email = $email;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
