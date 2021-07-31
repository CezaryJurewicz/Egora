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
        return ($user->user_type->isVerified && ($comment->comments->where('user_id',$user->id)->count() < 46));
    }
    
    public function delete(User $user, Comment $comment)
    {
        return (($comment->user_id == $user->id) || ($comment->commentable->is_user() && $comment->commentable->commentable->id == $user->id));
    }
    
    public function update(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id;
    }
    
    public function moderate(User $user, Comment $comment)
    {
        return ($user->user_type->isVerified && ($comment->user_id != $user->id) && (!$comment->is_user()));
    }
}
