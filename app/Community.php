<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    public function participants()
    {
        return $this->belongsToMany(User::class);
    }    
    
    public function scopeAllowed_To_Leave($query)
    {
        return $query->where('quit_allowed', 1);
    }
    
    public function scopeNot_Allowed_To_Leave($query)
    {
        return $query->where('quit_allowed', 0);
    }
}
