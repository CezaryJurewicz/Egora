<?php

namespace App\Policies;

use App\User;
use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\PolicyTrait;

class CommentPolicy
{
    use HandlesAuthorization, PolicyTrait;
    
    public function comment(User $user, Comment $comment)
    {
        return $this->allow();
    }
    
    public function delete(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id;
    }
}
