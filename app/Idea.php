<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Idea extends Model
{
    use SoftDeletes;
    
    protected $fillable = array('content', 'position');
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function nation()
    {
        return $this->belongsTo(Nation::class);
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
    
    public function getPositionSumAttribute($value)
    {
        return $this->liked_users()->sum('position');
    }
}
