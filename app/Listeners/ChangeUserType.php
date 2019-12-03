<?php

namespace App\Listeners;

use App\Events\PetitionSupportersChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\UserType;

class ChangeUserType
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
     * @param  PetitionSupportersChanged  $event
     * @return void
     */
    public function handle(PetitionSupportersChanged $event)
    {
        if ($event->petition->supporters->count() >= 23) {
            $type = UserType::where('class', 'officer')
                ->where('candidate', 0)
                ->where('verified', 1)
                ->where('fake', 0)
                ->first();
            
            $event->petition->user->user_type()->associate($type);
            $event->petition->user->save();
        }
    }
}
