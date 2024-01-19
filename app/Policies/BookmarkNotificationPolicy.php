<?php

namespace App\Policies;

use App\User;
use App\BookmarkNotification;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class BookmarkNotificationPolicy
{
    use HandlesAuthorization, PolicyTrait;

    public function viewAny(User $user)
    {
        return $this->deny();
    }
    
    public function delete(User $user, BookmarkNotification $notification)
    {
        return $notification->receiver_id == $user->id;
    }
}
