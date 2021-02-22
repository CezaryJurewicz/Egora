<?php

namespace App\Listeners;

use App\Events\UserInvitedToIdea;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\UserInvitedToIdea as UserInvitedToIdeaNotification;

class SendUserNotificationEmail
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
     * @param  UserInvitedToIdea  $event
     * @return void
     */
    public function handle(UserInvitedToIdea $event)
    {
        if ($event->notification->receiver->notifications) {
            // send email
            $event->notification
                ->receiver
                ->notify(new UserInvitedToIdeaNotification($event->notification));
        }
    }
}
