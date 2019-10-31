<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    
    public function supporters()
    {
        return $this->belongsToMany(User::class, 'petition_users');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
