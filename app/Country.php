<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = array('title');
    
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
