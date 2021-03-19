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
        // TODO: new rules
        return $user->can('comment', $comment->commentable);
    }
}
