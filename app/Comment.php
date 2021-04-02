<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CommentableTrait;

class Comment extends Model
{
    use SoftDeletes, CommentableTrait;
    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function votes()
    {
        return $this->belongsToMany(User::class)->withPivot('vote');
    }
}
