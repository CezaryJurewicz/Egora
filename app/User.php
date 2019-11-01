<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function isAdmin() 
    {
        return false;
    }
 
    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }
    
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
    
    public function search_names()
    {
        return $this->hasMany(SearchName::class);
    }
    
    public function active_search_names()
    {
        return $this->search_names()->active();
    }
    
    public function user_type()
    {
        return $this->belongsTo(UserType::class);
    }
    
    public function createdDate()
    {
        return (new Carbon($this->created_at))->diffForHumans();//format('H:i d.m.y');
    }
    
    public function updatedDate()
    {
        return (new Carbon($this->updated_at))->diffForHumans();
    }
    
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
