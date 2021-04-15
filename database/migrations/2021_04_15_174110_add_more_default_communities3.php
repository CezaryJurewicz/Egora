<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;
use App\User;

class AddMoreDefaultCommunities3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach([8 =>'Businesses to boycott', 9 =>'My favorite books', 10 =>'My personal values', 11 => 'Bucket list'] as $order => $title) {
            $community = Community::where('title', $title)->first();
            
            $affected = DB::table('community_user')
                ->where('community_id', $community->id)
                ->update(['order' => $order]);
        }
        
        $ids = [];
        foreach([7 =>'Media we can trust', 12 => 'Other public communities'] as $order => $title) {
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
