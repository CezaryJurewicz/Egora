<?php

namespace App\Listeners;

use App\Events\UserLeftComminity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\IdeaSupportHasChanged;

class DetachComminityIdeasFromUser
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
     * @param  UserLeftComminityNotification  $event
     * @return void
     */
    public function handle(UserLeftComminity $event)
    {
        $ideas = $event->user->liked_ideas()->where('idea_user.community_id', $event->community->id)->get();
        $event->user->liked_ideas()->detach($ideas);
        foreach ($ideas as $idea) {
            event(new IdeaSupportHasChanged($event->user, $idea));
        }        
    }
}
