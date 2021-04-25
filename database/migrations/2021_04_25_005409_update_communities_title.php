<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;

class UpdateCommunitiesTitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        $list = ['Egora developement', "World's biggest challenges", 'Shaping culture', 
            'Media we can trust', 'Businesses to boycott', 'My favorite books', 
            'My personal values', 'Bucket list', 'Other public communities'];
        
        foreach($list as $title) {
            $community = Community::where('title', $title)->first();

            if ($community) {
                $community->title = $title;
                $community->save();
            }            
        }
        
        $list = ['Truths we should all know' => 'Truths we all should know', 
            'Stories we should all hear' => 'Stories we all should hear'];
        
        foreach( $list as $old => $title) {
            $community = Community::where('title', $old)->first();

            if ($community) {
                $community->title = $title;
                $community->save();
            }            
        }
        
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
