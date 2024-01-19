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

class IdeaBookmarked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $egora_id;
    public $bookmark;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bookmark $bookmark)
    {
        $this->egora_id = $bookmark->idea->egora_id;
        $this->bookmark = $bookmark;
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
