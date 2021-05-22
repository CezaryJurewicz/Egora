<?php

namespace App\Listeners;

use App\Events\BeforeUserNationChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveAdministrativeSubdivisions
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
        $subdivisions = $event->user->subdivisions;

        $ids = $subdivisions->pluck('id')->toArray();
        $event->user->subdivisions()->toggle($ids);
    }
}
