<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = array('name', 'type', 'value');
        
    protected $hidden = array(
        'created_at','updated_at'
    );
    
}
