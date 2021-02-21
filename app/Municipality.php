<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use SoftDeletes;
 
    protected $fillable = array('title');
        
    protected $hidden = array(
        'created_at','updated_at','deleted_at'
    );
    
    public function users()
    {
        return $this->hasMany(User::class);
    }    
}
