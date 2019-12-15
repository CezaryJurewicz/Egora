<?php

namespace App\Listeners;

use App\Events\UserNameChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\UserType;

class RemoveUserVerification
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
     * @param  UserNameChanged  $event
     * @return void
     */
    public function handle(UserNameChanged $event)
    {
        $type = UserType::where('class', $event->user->user_type->class)
                ->where('verified', 0)
                ->where('former', $event->user->user_type->former)
                ->first();
        
        $event->user->user_type()->associate($type);
        $event->user->save();
    }
}
