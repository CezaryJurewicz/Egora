<?php 
namespace App\Traits;

use App\Comment;

trait CommentableTrait {

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function addComment($message, $user_id)
    {
        $comment = new Comment();
        $comment->message = $message;
        $comment->user_id = $user_id;

        $this->comments()->save($comment);

        return $comment;
    }

}