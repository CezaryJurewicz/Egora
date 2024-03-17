<?php

namespace App\Listeners;

use App\Events\UserLeftComminity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveComminityApprovalRatingVotes
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
     * @param  UserLeftComminity  $event
     * @return void
     */
    public function handle(UserLeftComminity $event)
    {
        $event->user->approval_ratings()
        ->whereHas('idea',function($q) use ($event) {
            $q->where('community_id', $event->community->id);
        })
        ->delete();
    }
}
