<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    
}
