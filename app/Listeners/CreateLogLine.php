<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\LogLine;

class CreateLogLine
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->notification) {
            $line = new LogLine();
            $line->user_id = $event->notification->receiver_id;
            $line->egora_id = $event->notification->idea->egora_id;
            $line->created_at = $event->notification->created_at;
            $event->notification->logline()->save($line);
        }
    }
}
