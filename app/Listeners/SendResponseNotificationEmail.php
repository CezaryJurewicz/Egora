<?php

namespace App\Listeners;

use App\Events\UserRespondedToInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\UserResponseNotification;

class SendResponseNotificationEmail
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
     * @param  UserRespondedToInvitation  $event
     * @return void
     */
    public function handle(UserRespondedToInvitation $event)
    {
        if ($event->notification->receiver->notifications) {
            // send email
            $event->notification
                ->receiver
                ->notify(new UserResponseNotification($event->notification));
        }
    }
}
