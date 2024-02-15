<?php

namespace App\Listeners;

use App\Events\IdeaBookmarked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\BookmarkNotification;
use App\Notifications\IdeaBookmarkedNotificationEmail;
use App\LogLine;

class CreateIdeaBookmarkedNotification
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
     * @param  IdeaBookmarked  $event
     * @return void
     */
    public function handle(IdeaBookmarked $event)
    {
        if ( $event->notification && $event->bookmark->user_id != $event->notification->sender->id ) 
        {
            $notification = new BookmarkNotification();
            $notification->egora_id = $event->egora_id;
            $notification->sender_id = $event->bookmark->user_id;
            $notification->receiver_id = $event->notification->sender->id;
            $notification->bookmark_id = $event->bookmark->id;
            $notification->message = 'Your idea was bookmarked.';
            $notification->save();

            $line = new LogLine();
            $line->user_id = $notification->receiver_id;
            $line->egora_id = $notification->egora_id;
            $line->created_at = $notification->created_at;
            $notification->logline()->save($line);

            $event->notification->delete();
            
            if ($notification->receiver->notifications) {
                $notification->receiver
                        ->notify(new IdeaBookmarkedNotificationEmail($notification));
            }
        }
    }
}
