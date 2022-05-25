<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Cascade;

class CommentNotification extends Model
{
    use SoftDeletes, Cascade;
    
    protected $cascade = ['logline'];
    
    public function logline()
    {
        return $this->morphOne(LogLine::class, 'loggable');
    }
    
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
