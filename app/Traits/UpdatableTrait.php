<?php 
namespace App\Traits;

trait UpdatableTrait {

    public function update_relation()
    {
        return $this->morphOne(\App\Update::class, 'updatable');
    }

}