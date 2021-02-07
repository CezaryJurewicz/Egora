<?php

namespace App\Listeners;

use App\Events\UserLikedIdeaFromNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\UserResponseLikeNotification;

class SendLikedIdeaNotificationEmail
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
     * @param  UserLikedIdeaFromNotification  $event
     * @return void
     */
    public function handle(UserLikedIdeaFromNotification $event)
    {
        $event->notification
            ->receiver
            ->notify(new UserResponseLikeNotification($event->notification));
    }
}
