<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Comment;

class CommentUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageBeforeUpdate;
    public $comment;
    public $egora_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, $messageBeforeUpdate)
    {
        $this->comment = $comment;
        $this->messageBeforeUpdate = $messageBeforeUpdate;
        
        if ($comment->commentable instanceof \App\Comment) {  
            $item = $comment->commentable->commentable;
        } else {
            $item = $comment->commentable;
        }
        
        if ($item instanceof \App\Idea) {
            $this->egora_id = $item->egora_id;
        } else if ($item instanceof \App\User) {
            $this->egora_id = config('egoras.default.id');
        } else {
            $this->egora_id = current_egora_id();
        }
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
