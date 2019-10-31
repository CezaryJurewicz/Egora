<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
//    public function country()
//    {
//        return $this->hasOneThrough(Country::class, City::class, 'country_id', 'id');
//    }
    
    public function country()
    {
        return $this->city->country();
    }

    
}
