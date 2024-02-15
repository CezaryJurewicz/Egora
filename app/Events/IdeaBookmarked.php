<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Bookmark;
use App\Notification as NotificationModel;

class IdeaBookmarked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $egora_id;
    public $bookmark;
    public $notification;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bookmark $bookmark, $notification = null )
    {
        $this->egora_id = $bookmark->idea->egora_id;
        $this->bookmark = $bookmark;
        $this->notification = $notification;
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
