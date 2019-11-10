<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchName extends Model
{
    protected $fillable = [
        'name', 'seachable', 'active',
    ];

    /**
     * Scope a query to only include active.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    
    /**
     * Scope a query to only include Searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchable($query)
    {
        return $query->where('searchable', 1);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
