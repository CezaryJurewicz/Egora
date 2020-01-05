<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = array('title', 'subtitle');
        
    protected $hidden = array(
        'created_at','updated_at','deleted_at'
    );
    
    public function getVerifiedTextAttribute()
    {
        return $this->attributes['verified']? 'verified' : 'unverified';
    }
    public function getCandidateTextAttribute()
    {
        return $this->attributes['candidate']? 'candidate' : '';
    }
    
    public function getFakeTextAttribute()
    {
        return $this->attributes['fake']? 'verified' : '';
    }
    
    public function getFormerTextAttribute()
    {
        return $this->attributes['former']? 'former' : '';
    }
    
    public function scopeVerified($query)
    {
        return $query->where('verified', 1);
    }
    
    public function scopeCandidate($query)
    {
        return $query->where('candidate', 1);
    }
    
    public function scopeFake($query)
    {
        return $query->where('fake', 1);
    }
    
    public function getIsIlpAttribute()
    {
        return ($this->attributes['class'] != 'user' && $this->attributes['candidate'] == 0 && $this->attributes['former'] == 0);
    }
    
    public function getIsOfficerAttribute()
    {
        return ($this->attributes['class'] == 'officer' && $this->attributes['candidate'] == 0 && $this->attributes['former'] == 0);
    }
    
    public function getIsPetitionerAttribute()
    {
        return ($this->attributes['class'] == 'petitioner' && $this->attributes['candidate'] == 0 && $this->attributes['former'] == 0);
    }
    
    public function getIsVerifiedAttribute()
    {
        return $this->attributes['verified'];
    }
}
