<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }
}
