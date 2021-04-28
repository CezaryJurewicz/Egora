<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\CommentNotification;
use App\Notifications\CommentNotificationEmail;

class CreateCommentNotification
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
     * @param  CommentResponseAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        if ($event->comment->is_response() && $event->comment->user_id!==$event->comment->commentable->user_id) {
            $notification = new CommentNotification();
            $notification->egora_id = $event->egora_id;
            $notification->sender_id = $event->comment->user_id;
            $notification->receiver_id = $event->comment->commentable->user_id;
            $notification->comment_id = $event->comment->id;
            $notification->message = ' responded to your comment.';
            $notification->save();
            
            $notification->receiver
                ->notify(new CommentNotificationEmail($notification));
        } else {
            if (preg_match('/\@\<([a-zA-Z0-9_\-\s]*)\>/', $event->comment->message, $output_array) && $search_name= \App\SearchName::where('name',$output_array[1])->first()) {
            
                $notification = new CommentNotification();
                $notification->egora_id = $event->egora_id;
                $notification->sender_id = $event->comment->user_id;
                $notification->receiver_id = $search_name->user->id;
                $notification->comment_id = $event->comment->id;
                $notification->message = ' mentioned you in their comment.';
                $notification->save();

                $notification->receiver
                    ->notify(new CommentNotificationEmail($notification));
            }
        }
    }
}
