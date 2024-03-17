<?php

namespace App\Listeners;

use App\Events\UserLeftingMunicipality;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveMunicipalityApprovalRatingVotes
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
     * @param  UserLeftingMunicipality  $event
     * @return void
     */
    public function handle(UserLeftingMunicipality $event)
    {
        $event->user->approval_ratings()
        ->whereHas('idea',function($q) use ($event) {
            $q->where('municipality_id', $event->user->municipality->id);
        })
        ->delete();
    }
}
