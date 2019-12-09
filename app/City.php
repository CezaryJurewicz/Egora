<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = array('title');
        
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
