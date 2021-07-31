<?php

namespace App\Listeners;

use App\Events\StatusAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Update;

class CreateStatusUpdates
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
     * @param  StatusAdded  $event
     * @return void
     */
    public function handle(StatusAdded $event)
    {   
       foreach($event->status->user->followers as $user){
            if ($user->updates->count() < 64) {
                $update = new Update();
                $update->user_id = $user->id;
                $update->egora_id = $event->egora_id;
                $update->type = 'status';
                $event->status->update_relation()->save($update);
            }
       }
    }
}
