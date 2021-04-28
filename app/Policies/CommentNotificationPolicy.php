<?php

namespace App\Policies;

use App\User;
use App\CommentNotification;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class CommentNotificationPolicy
{
    use HandlesAuthorization, PolicyTrait;

    public function viewAny(User $user)
    {
        return $this->allow();
    }
    
    public function delete(User $user, CommentNotification $notification)
    {
        return $notification->receiver_id == $user->id;
    }
}
