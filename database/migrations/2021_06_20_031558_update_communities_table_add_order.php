<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Community;

class UpdateCommunitiesTableAddOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', function(Blueprint $table) {
            $table->integer('order')->nullable()->after('title');
        });
        
        foreach( communities_list() as $order => $title) {
            $community = Community::where('title', $title)->first();
            
            if ($community) {
                $affected = DB::table('communities')
                    ->where('id', $community->id)
                    ->update(['order' => $order]);

                $affected = DB::table('community_user')
                    ->where('community_id', $community->id)
                    ->update(['order' => $order]);
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
        Schema::table('communities', function(Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
