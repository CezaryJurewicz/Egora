<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens;

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
        'last_online_at' => 'datetime',
        'ip_updated_at' => 'datetime'
    ];
    
    protected $appends = array('active_search_name');
    
    public function isAdmin() 
    {
        return false;
    }
    
    public function isGuardian() 
    {
        return false;
    }

    public function default_lead()
    {
        return $this->hasOne(DefaultLead::class);
    }
    
    public function campaign()
    {
        return $this->hasOne(Campaign::class);
    }
    
    public function petition()
    {
        return $this->hasOne(Petition::class);
    }
    
    public function supporting()
    {
        return $this->belongsToMany(Petition::class, 'petition_users');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }
    
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
    
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
    
    public function search_names()
    {
        return $this->hasMany(SearchName::class);
    }
    
    public function followingUser(User $user)
    {
        return $this->following->pluck('pivot.user_id')->contains($user->id);
    }
        
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }
    
    public function disqualifyingUser(User $user)
    {
        return $this->disqualifying_users->pluck('pivot.user_id')->contains($user->id);
    }
    
    public function disqualifying_users()
    {
        return $this->belongsToMany(User::class, 'disqualified_users', 'disqualified_user_id', 'user_id');
    }
    
    public function disqualified_by()
    {
        return $this->belongsToMany(User::class, 'disqualified_users', 'user_id', 'disqualified_user_id');
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
    
    public function verified_users()
    {
        return $this->belongsToMany(User::class, 'users_verified_log', 'officer_id', 'user_id');
    }
    
    public function verified_users_aday()
    {
        return $this->verified_users()
                ->whereBetween('users_verified_log.created_at', [(new Carbon())->subDays(1), (new Carbon())]);   
    }
    
    public function getActiveSearchNameAttribute()
    {
        return $this->active_search_names->isNotEmpty()?$this->active_search_names->first()->name:$this->id;
    }
    
    public function getActiveSearchNameHashAttribute()
    {
        return $this->active_search_names->isNotEmpty()?$this->active_search_names->first()->hash:$this->id;
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
    
    public function lastOnlineAtDate()
    {
        return (new Carbon($this->last_online_at))->diffForHumans();
    }
    
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
        
    public function verification_id()
    {
        return $this->hasOne(Passport::class);
    }
    
    // I muted
    public function notifications_disabled()
    {
        return $this->belongsToMany(User::class, 'disabled_notifications', 'user_id', 'disabled_user_id', 'id');
    }
    
    // I was muted by
    public function notifications_disabled_by()
    {
        return $this->belongsToMany(User::class, 'disabled_notifications', 'disabled_user_id', 'user_id', 'id');
    }
    
    public function liked_ideas()
    {
        return $this->belongsToMany(Idea::class)->withPivot('position', 'order', 'community_id')
                ->orderBy('pivot_order', 'desc');
    }
    
    public function communities()
    {
        return $this->belongsToMany(Community::class)->withPivot('order')->orderBy('on_registration', 'desc')->orderBy('order')->orderBy('title');
    }
    
    public function subdivisions()
    {
        return $this->belongsToMany(Subdivision::class)->withPivot('order')->orderBy('order')->orderBy('title');
    }
    
    public function communities_not_allowed_to_leave()
    {
        return $this->communities()->not_allowed_to_leave();
    }
    
    public function communities_allowed_to_leave()
    {
        return $this->communities()->allowed_to_leave();
    }
    
    public function scopeRecent($query)
    {
        return $query->whereDate('users.last_online_at', '>', now()->subDays(23));
    }
    
    public function scopeVisible($query)
    {
        return is_egora() ? $query->where('visible', 1) : $query;
    }
    
    public function user_notifications_new()
    {
        return $this->user_notifications()
                ->whereDate('notifications.created_at', '>=', now()->subDays(46));
    }
    
    public function user_notifications()
    {
        return $this->belongsToMany(User::class, 'notifications', 'sender_id', 'receiver_id')
                ->withPivot( 'idea_id', 'notification_id', 'viewed', 'notification_preset_id', 'created_at')
                ->orderBy('pivot_created_at', 'desc');

    }
    
    public function user_received_notifications()
    {
        return $this->belongsToMany(User::class, 'notifications', 'receiver_id', 'sender_id')
                ->withPivot( 'idea_id', 'notification_id', 'viewed', 'notification_preset_id', 'created_at')
                ->orderBy('pivot_created_at', 'desc');

    }
    
    public function infoEmpty()
    {
        return is_null($this->email_address) 
            && is_null($this->phone_number)
            && is_null($this->social_media_1)
            && is_null($this->social_media_2)
            && is_null($this->messenger_1)
            && is_null($this->messenger_2)
            && is_null($this->other_1)
            && is_null($this->other_2);
    }
}
