<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 

class Meeting extends Model
{
    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i:s',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function country()
    {
        return $this->city->country();
    }

    public function scopeFromNow($q) 
    {
        $q->where('start_at', '>', (new Carbon())->format('Y-m-d H:i'));
    }
    
}
