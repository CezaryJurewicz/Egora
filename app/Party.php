<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use SoftDeletes;
 
    protected $fillable = array('title');
        
    protected $hidden = array(
        'created_at','updated_at','deleted_at'
    );
    
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
