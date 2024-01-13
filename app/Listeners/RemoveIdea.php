<?php

namespace App\Listeners;

use App\Events\IdeaSupportHasChanged;
use App\Events\IdeaUnbookmarked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveIdea
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
     * @param  IdeaSupportHasChanged || IdeaUnbookmarked $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->idea->liked_users->count() == 0 && $event->idea->bookmarkers->count() == 0) {
            $event->idea->forceDelete();
        }
    }
}
