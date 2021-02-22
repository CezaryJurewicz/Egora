<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
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
    
    public function parent()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
    
    public function notification_preset()
    {
        return $this->belongsTo(NotificationPreset::class);
    }
    
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
    
    public function scopeNew($query)
    {
        return $query->whereDate('created_at', '>=', now()->subDays(23));
    }
    
    public function getInviteAttribute() {
        return is_null($this->notification_preset_id) && is_null($this->notification_id);
    }
    
    public function getResponseAttribute() {
        return !is_null($this->notification_id) && !is_null($this->notification_preset_id);
    }
    
}
