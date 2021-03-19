<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CommentableTrait;

class Idea extends Model
{
    use SoftDeletes, CommentableTrait;
    
    protected $fillable = array('content', 'position', 'order', 'egora_id');
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }
    
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
    
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
    
    public function createdDate()
    {
        return (new Carbon($this->created_at))->diffForHumans();//format('H:i d.m.y');
    }
    
    public function updatedDate()
    {
        return (new Carbon($this->updated_at))->diffForHumans();
    }
    
    public function liked_users()
    {
        return $this->belongsToMany(User::class)->withPivot('position');
    }
    
    public function liked_users_visible()
    {
        return $this->liked_users()->visible();
    }
    
    public function getPositionSumAttribute($value)
    {
        return $this->liked_users->sum('position');
    }
}
