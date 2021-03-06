<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    public function participants()
    {
        return $this->belongsToMany(User::class)->withPivot('order');
    }       
    
    public function nation()
    {
        return $this->belongsTo(Nation::class);
    }

}
