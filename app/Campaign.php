<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_users');
    }
}
