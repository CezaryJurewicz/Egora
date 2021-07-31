<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CommentableTrait;
use App\Traits\UpdatableTrait ;

class Comment extends Model
{
    use SoftDeletes, CommentableTrait, UpdatableTrait;
    
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
    
    public function is_response() 
    {
        return ($this->commentable instanceof \App\Comment);
    }
    
    public function scopeCounted($query)
    {
        return $query->whereNull('deleted_at')->orWhereDate('deleted_at', '>', now()->subDays(46));
    }
}
