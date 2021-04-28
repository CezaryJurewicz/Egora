<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentNotification extends Model
{
    use SoftDeletes;

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class);
    }
    
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
    
}
