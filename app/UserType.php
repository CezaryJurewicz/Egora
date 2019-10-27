<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = array('title', 'subtitle');
        
    protected $hidden = array(
        'created_at','updated_at','deleted_at'
    );
}
