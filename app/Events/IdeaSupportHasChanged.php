<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Idea;
use App\User;

class IdeaSupportHasChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idea;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Idea $idea)
    {
        $this->idea = $idea;
        $this->user = $user;
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
