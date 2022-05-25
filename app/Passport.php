<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Cascade;

class Passport extends Model
{
    use Cascade;
    
    protected $cascade = ['image'];

    protected $fillable = array('user_id');

    protected $hidden = array(
        'mediable_type', 'mediable_id'
    );
    
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
