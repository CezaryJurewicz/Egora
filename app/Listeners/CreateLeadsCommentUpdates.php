<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Update;

class CreateLeadsCommentUpdates
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        if ($event->comment->commentable instanceof \App\Comment) {  
            $item = $event->comment->commentable->commentable;
        } else {
            $item = $event->comment->commentable;
        }
        
        foreach($event->comment->user->followers as $user){
            if ($user->updates->count() < 99 && (((config('egoras.community.id') == $event->egora_id) && $user->communities->contains($item->community)) || (config('egoras.community.id') != $event->egora_id)) ) {
                $update = new Update();
                $update->user_id = $user->id;
                $update->egora_id = $event->egora_id;

                if ($event->comment->is_response()) {
                    $update->type = 'subcomment';
                } else {
                    $update->type = 'comment';
                }
                $event->comment->update_relation()->save($update);
            }
        }
    }
}
