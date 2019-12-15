<?php

namespace App\Listeners;

use App\Events\UserLeftIlp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveOfficerPetition
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
     * @param  UserLeftIlp  $event
     * @return void
     */
    public function handle(UserLeftIlp $event)
    {
        if($event->user->petition)
            $event->user->petition->delete();
    }
}
