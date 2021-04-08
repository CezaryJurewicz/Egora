<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;
use App\User;

class AddMoreDefaultCommunities2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach([5 =>'Citizen Assembly', 8 =>'My favorite books', 9 =>'My personal values'] as $order => $title) {
            $community = Community::where('title', $title)->first();
            
            $affected = DB::table('community_user')
                ->where('community_id', $community->id)
                ->update(['order' => $order]);
        }
        
        $ids = [];
        foreach([4 =>"World's biggest challenges", 7 =>'Businesses to boycott', 10 =>'Bucket list'] as $order => $title) {
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
