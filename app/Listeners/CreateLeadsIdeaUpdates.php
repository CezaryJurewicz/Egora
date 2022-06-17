<?php

namespace App\Listeners;

use App\Events\UserIdeologicalProfileChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Update;

class CreateLeadsIdeaUpdates
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
     * @param  UserIdeologicalProfileChanged  $event
     * @return void
     */
    public function handle(UserIdeologicalProfileChanged $event)
    {
        foreach($event->user->followers as $user){
            if ($user->updates->count() < 99 && ((is_egora('community') && $user->communities->contains($event->idea->community)) || !is_egora('community'))) {
                $update = new Update();
                $update->user_id = $user->id;
                $update->egora_id = $event->idea->egora_id;
                $update->from_id = $event->user->id;
                $update->type = 'idea';
                $event->idea->update_relation()->save($update);
            }
        }
    }
}
