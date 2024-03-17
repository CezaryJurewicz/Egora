<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalRating extends Model
{
    protected $fillable = [
        'user_id', 'idea_id', 'score',
    ];

    protected $hidden = [
       'id', 'created_at', 'updated_at',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}