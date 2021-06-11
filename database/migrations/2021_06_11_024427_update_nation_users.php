<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $to_nation = App\Nation::where('title', 'United States of America')->first();
        
        $users = App\User::whereHas('nation', function($q) {
               $q->whereIn('title', array_nation_USA());
        })->update(['nation_id' => $to_nation->id]);
        
        $ideas = App\Idea::whereHas('nation', function($q) {
               $q->whereIn('title', array_nation_USA());
        })->update(['nation_id' => $to_nation->id]);
        
        $subdivisions = App\Subdivision::whereHas('nation', function($q) {
               $q->whereIn('title', array_nation_USA());
        })->update(['nation_id' => $to_nation->id]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
