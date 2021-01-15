<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdeasTableAddCommunityIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ideas', function(Blueprint $table) {
            $table->bigInteger('community_id')->unsigned()->nullable()->after('nation_id');
            
            $table->foreign('community_id')->references('id')
                    ->on('communities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ideas', function(Blueprint $table) {
            $table->dropForeign(['community_id']);
            $table->dropColumn('community_id');
        });  
    }
}
