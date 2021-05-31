<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends User
{

    public function isAdmin() 
    {
        return (!$this->guardianship);
    }
    
    public function isGuardian() 
    {
        return $this->guardianship;
    }
}
