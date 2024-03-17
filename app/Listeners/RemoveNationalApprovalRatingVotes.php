<?php

namespace App\Listeners;

use App\Events\BeforeUserNationChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveNationalApprovalRatingVotes
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
        $event->user->approval_ratings()
        ->whereHas('idea',function($q) use ($event) {
            $q->where('nation_id', $event->user->nation->id);
        })
        ->delete();
    }
}
