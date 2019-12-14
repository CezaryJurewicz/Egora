<?php

namespace App\Listeners;

use App\Events\BeforeUserNationChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\IdeaSupportHasChanged;

class RemoveToggleLikedIdeas
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
        
        $user->load(['liked_ideas' => function($q) use ($user){
            $q->whereHas('nation', function($q) use ($user){
                $q->where('id', $user->nation->id);
            });
        }]);
        $ideas = $user->liked_ideas;

        $ids = $ideas->pluck('id')->toArray();
        $user->liked_ideas()->toggle($ids);

        foreach($ideas as $idea)
        {
            event(new IdeaSupportHasChanged($idea));
        }
    }
}
