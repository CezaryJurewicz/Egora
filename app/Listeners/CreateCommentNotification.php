<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\CommentNotification;
use App\Notifications\CommentNotificationEmail;
use App\SearchName;
use App\LogLine;

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
        $search_name_ids = [];
        
        if (preg_match_all('/\{([a-zA-Z0-9_\-\s]*)\}/', $event->comment->message, $output_array)) {
            foreach(array_unique($output_array[1]) as $name){
                if ($search_name=SearchName::where('name', $name)->first()) {
                    if (!$event->comment->user->notifications_disabled_by->contains($search_name->user)) {
                        $notification = new CommentNotification();
                        $notification->egora_id = $event->egora_id;
                        $notification->sender_id = $event->comment->user_id;
                        $notification->receiver_id = $search_name->user->id;
                        $notification->comment_id = $event->comment->id;
                        $notification->message = ' mentioned you in their comment.';
                        $notification->save();

                        $line = new LogLine();
                        $line->user_id = $notification->receiver_id;
                        $line->egora_id = $notification->egora_id;
                        $line->created_at = $notification->created_at;
                        $notification->logline()->save($line);
                        
                        $search_name_ids[] = $search_name->user->id;

                        if ($notification->receiver->сomment_notifications) {
                            $notification->receiver
                                ->notify(new CommentNotificationEmail($notification));
                        }
                    }
                }
            }
        }
        
        if ($event->comment->is_response() && $event->comment->user_id != $event->comment->commentable->user_id && !in_array($event->comment->commentable->user_id, $search_name_ids)) {
            if (!$event->comment->user->notifications_disabled_by->contains($event->comment->commentable->user)) {
                $notification = new CommentNotification();
                $notification->egora_id = $event->egora_id;
                $notification->sender_id = $event->comment->user_id;
                $notification->receiver_id = $event->comment->commentable->user_id;
                $notification->comment_id = $event->comment->id;
                if ($event->comment->commentable->is_user()) {
                    $notification->message = ' responded to your status.';
                } else {
                    $notification->message = ' responded to your comment.';
                }
                $notification->save();

                $line = new LogLine();
                $line->user_id = $notification->receiver_id;
                $line->egora_id = $notification->egora_id;
                $line->created_at = $notification->created_at;
                $notification->logline()->save($line);

                if ($notification->receiver->сomment_notifications) {
                    $notification->receiver
                        ->notify(new CommentNotificationEmail($notification));
                }
            }
        }

    }
}
