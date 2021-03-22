<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;
use App\User;

class AddMoreDefaultCommunities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ids = [];
        foreach([4=>'My personal values',5=>'My favorite books',6=>'Shaping Culture',7=>'Citizen Assembly'] as $order => $title) {
            $community = Community::where('title', $title)->first();

            if (!$community) {
                $community = new Community();
                $community->title = $title;
            }            
            $community->on_registration = true;
            $community->quit_allowed = false;
            $community->save();
            
            $ids[$community->id] = ['order' => $order];
        }
        
        $users = User::get();
        foreach ($users as $user){
            $user->communities()->syncWithoutDetaching($ids);
        }

        foreach([2=>'Truths we should all know',3=>'Stories we should all hear', 1=>'Egora Development'] as $order => $title) {
            $community = Community::where('title', $title)->first();
            
            $affected = DB::table('community_user')
                ->where('community_id', $community->id)
                ->update(['order' => $order]);
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
