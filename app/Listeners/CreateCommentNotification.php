<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\CommentNotification;
use App\Notifications\CommentNotificationEmail;
use App\SearchName;

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
        if ($event->comment->is_response() && $event->comment->user_id != $event->comment->commentable->user_id) {
            if (!$event->comment->user->notifications_disabled_by->contains($event->comment->commentable->user)) {
                $notification = new CommentNotification();
                $notification->egora_id = $event->egora_id;
                $notification->sender_id = $event->comment->user_id;
                $notification->receiver_id = $event->comment->commentable->user_id;
                $notification->comment_id = $event->comment->id;
                $notification->message = ' responded to your comment.';
                $notification->save();
                
                if ($notification->receiver->сomment_notifications) {
                    $notification->receiver
                        ->notify(new CommentNotificationEmail($notification));
                }
            }
        }
        
        if (preg_match_all('/\@([a-zA-Z0-9_\-\s]*)[:,]/', $event->comment->message, $output_array)) {
            foreach(array_unique($output_array[1]) as $name){
                if ($search_name=SearchName::where('name', $name)->first()) {
                    if (!$event->comment->user->notifications_disabled_by->contains($search_name->user)) {
                        if (($event->comment->is_response() && $search_name->user->id !== $event->comment->commentable->user_id) || (!$event->comment->is_response())) {
                            $notification = new CommentNotification();
                            $notification->egora_id = $event->egora_id;
                            $notification->sender_id = $event->comment->user_id;
                            $notification->receiver_id = $search_name->user->id;
                            $notification->comment_id = $event->comment->id;
                            $notification->message = ' mentioned you in their comment.';
                            $notification->save();
                            
                            if ($notification->receiver->сomment_notifications) {
                                $notification->receiver
                                    ->notify(new CommentNotificationEmail($notification));
                            }
                        }
                    }
                }
            }
        }
    }
}
