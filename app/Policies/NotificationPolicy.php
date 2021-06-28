<?php

namespace App\Policies;

use App\Notification;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class NotificationPolicy
{
    use HandlesAuthorization, PolicyTrait;

    /**
     * Determine whether the user can view any notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->deny();
    }

    /**
     * Determine whether the user can view the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function view(User $user, Notification $notification)
    {
        return $notification->receiver_id == $user->id;
    }

    /**
     * Determine whether the user can create notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can update the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function update(User $user, Notification $notification)
    {
        return $this->allow();
    }

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function delete(User $user, Notification $notification)
    {
        return $notification->receiver_id == $user->id;
    }

    /**
     * Determine whether the user can restore the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function restore(User $user, Notification $notification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function forceDelete(User $user, Notification $notification)
    {
        //
    }
    
    public function invite_response(User $user, Notification $notification) 
    {        
        return ( $user->user_received_notifications->first(function ($v, $k) use ($user, $notification) {
                        return $v->pivot->receiver_id == $user->id && $v->pivot->idea_id == $notification->idea->id;
                    })
                );
    }

}
