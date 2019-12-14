<?php

namespace App\Listeners;

use App\Events\BeforeUserNationChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PetitionSupportersChanged;

class RemoveOfficerPetitionSupport
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
     * @param  BeforeUserNationChanged  $event
     * @return void
     */
    public function handle(BeforeUserNationChanged $event)
    {
        $user = $event->user;
        $petition = $user->supporting->first();
        if ($petition) {
            $user->supporting()->sync([]);
            event(new PetitionSupportersChanged($petition, $user));
        }

    }
}
