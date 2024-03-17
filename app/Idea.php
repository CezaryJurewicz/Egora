<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CommentableTrait;
use App\Traits\UpdatableTrait;
use App\Traits\Cascade;

class Idea extends Model
{
    use SoftDeletes, CommentableTrait, UpdatableTrait, Cascade;
    
    public $cascade = ['comments', 'update_relation'];
    
    protected $fillable = array('content', 'position', 'order', 'egora_id');
    
    public function source()
    {
        return $this->belongsTo(Idea::class, 'idea_id');
    }
    
    public function derivatives()
    {
        return $this->hasMany(Idea::class);
    }
    
    public function approval_ratings()
    {
        return $this->hasMany(ApprovalRating::class);
    }
    
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
    
    public function moderators()
    {
        return $this->liked_users()->where('idea_user.order', '<', 0 );
    }
    
    public function liked_users_visible()
    {
        return $this->liked_users()->visible();
    }
    
    public function getPositionSumAttribute($value)
    {
        return $this->liked_users->sum('position');
    }
    
    public function bookmarkers()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withPivot('position');
    }
    
    public function is_bookmarked($user)
    {
        if(is_null($this->community) && is_null($this->municipality)) {
            $ideas = $user->bookmarked_ideas->whereNotNull('nation_id');
        } else if(!is_null($this->community)) {
            $ideas = $user->bookmarked_ideas->where('community_id', $this->community->id);
        } else if(!is_null($this->municipality)) {
            $ideas = $user->bookmarked_ideas->whereNotNull('municipality_id');
        }
        
        return $ideas->where('id', $this->id)->isNotEmpty();
    }
}
