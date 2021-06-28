<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogLine extends Model
{
    use SoftDeletes;
    
    public function loggable()
    {
        return $this->morphTo();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeNew($query)
    {
        return $query->whereDate('created_at', '>=', now()->subDays(23));
    }

}
