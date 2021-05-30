<?php

namespace App\Listeners;

use App\Events\UserLeftingMunicipality;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\IdeaSupportHasChanged;

class DetachMunicipalityIdeasFromUser
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
        $ideas = $event->user->liked_ideas()->where('ideas.municipality_id', $event->user->municipality->id)->get();
        $event->user->liked_ideas()->detach($ideas);
        foreach ($ideas as $idea) {
            event(new IdeaSupportHasChanged($event->user, $idea));
        } 
    }
}
