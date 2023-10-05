<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;
use App\User;

class UpdateCommunitiesAddFourTitles extends Migration
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
        
        $c = App\Community::where('title','Businesses to boycott')->first();
        
        $community = App\Community::where('title','Political vocabulary')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'Political vocabulary';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];
        
        $c = App\Community::where('title','My favorite books')->first();
        
        $community = App\Community::where('title','My favorite films')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'My favorite films';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];
        
        $community = App\Community::where('title','My favorite recipes')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'My favorite recipes';
        $community->order = $c->order + 2;
        $community->on_registration = true;
        $community->quit_allowed = false;
        $community->save();
        $ids[$community->id] = ['order' => $community->order];
         
        $c = App\Community::where('title','My personal values')->first();
        
        $community = App\Community::where('title','Relationship advice')->first();
        if (is_null($community)) { 
            $community = new Community();
        }
        $community->title = 'Relationship advice';
        $community->order = $c->order + 1;
        $community->on_registration = true;
        $community->quit_allowed = false;
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
        
        
        // add ids to all users - community_user
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
