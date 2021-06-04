<?php

namespace App\Listeners;

use App\Events\PetitionSupportersChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePetitionStatus
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
        $event->petition->load(['supporters'=> function($q) {
            $q->recent();
        }]);
        
        if ($event->petition->supporters->count() >= 5) {
            $event->petition->finished = true;
        } else {
            $event->petition->finished = false;
        }
        
        $event->petition->save();
    }
}
