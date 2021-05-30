<?php

namespace App\Listeners;

use App\Events\UserIdeologicalProfileChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSeniorityTime
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
     * @param  IdeaSupportHasChanged  $event
     * @return void
     */
    public function handle(UserIdeologicalProfileChanged $event)
    {
        if ($idea = $event->user->liked_ideas->where('id', $event->idea->id)->first()) {
            if ($event->user->campaign && $event->idea->egora_id == config('egoras.default.id')
                    && $idea->pivot->order > 0) {
                $event->user->campaign->touch();
            }
        }
    }
}
