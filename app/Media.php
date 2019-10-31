<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = array('original_name', 'user_id', 'filename', 'mime', 'hash', 'disk', 'url');
        
    protected $hidden = array(
        'created_at','updated_at',
        'mediable_type', 'mediable_id'
    );
        
    public function mediable()
    {
        return $this->morphTo();
    }
}
