<?php

namespace App\Listeners;

use App\Events\SearchNameChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveFollowers
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
     * @param  SearchNameChanged  $event
     * @return void
     */
    public function handle(SearchNameChanged $event)
    {
        $event->user->followers()->sync([]);
    }
}
