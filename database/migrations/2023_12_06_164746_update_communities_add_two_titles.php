<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;
use App\User;

class UpdateCommunitiesAddTwoTitles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ids = [];
        
        $communities = Community::whereNotNull('order')->get()->all();
        foreach ($communities as $c) {
            $c->order = $c->order * 10;
            $c->save();
        }
        
        $c = App\Community::where('title','My favorite films')->first();
        
        $community = App\Community::where('title','My favorite shows')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'My favorite shows';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->bookmark_limit = 100;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];
        
        $c = App\Community::where('title','My favorite shows')->first();
        
        $community = App\Community::where('title','My favorite podcasts')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'My favorite podcasts';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->bookmark_limit = 100;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];

        $c = App\Community::where('title','My favorite podcasts')->first();
        
        $community = App\Community::where('title','My favorite games')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'My favorite games';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->bookmark_limit = 100;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];
        
        //add new communities to all users
        $users = User::get();
        foreach ($users as $user){
            $user->communities()->syncWithoutDetaching($ids);
        }

        //update new order
        foreach($communities as $community) {
            $affected = DB::table('community_user')
                ->where('community_id', $community->id) 
                ->update(['order' => $community->order]);
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
