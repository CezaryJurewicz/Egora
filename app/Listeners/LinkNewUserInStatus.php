<?php

namespace App\Listeners;

use App\Events\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\CommentNotification;
use App\Notifications\CommentNotificationEmail;
use App\SearchName;
use App\LogLine;

class LinkNewUserInStatus
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
     * @param  StatusUpdated  $event
     * @return void
     */
    public function handle(StatusUpdated $event)
    {
        preg_match_all('/\{([a-zA-Z0-9_\-\s]*)\}/', $event->messageBeforeUpdate, $before_list);
        
        if (preg_match_all('/\{([a-zA-Z0-9_\-\s]*)\}/', $event->status->message, $output_array)) {
            foreach(array_unique($output_array[1]) as $name){
                if (!in_array($name, $before_list[1]) && $search_name=SearchName::where('name', $name)->first()) {
                    if (!$event->status->user->notifications_disabled_by->contains($search_name->user)) {
                        $notification = new CommentNotification();
                        $notification->egora_id = $event->egora_id;
                        $notification->sender_id = $event->status->user_id;
                        $notification->receiver_id = $search_name->user->id;
                        $notification->comment_id = $event->status->id;
                        $notification->message = ' mentioned you in their status.';
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
    }
}
