<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    
    public function updatable()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function from()
    {
        return $this->belongsTo(User::class);
    }
}
