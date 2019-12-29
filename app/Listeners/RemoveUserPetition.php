<?php

namespace App\Listeners;

use App\Events\UserNameChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveUserPetition
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
     * @param  UserNameChanged  $event
     * @return void
     */
    public function handle(UserNameChanged $event)
    {
        if ($event->user->user_type->isPetitioner) 
        {
           $event->user->petition->delete();
        }
        
    }
}
