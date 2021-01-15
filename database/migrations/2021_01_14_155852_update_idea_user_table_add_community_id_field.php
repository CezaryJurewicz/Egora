<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdeaUserTableAddCommunityIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Excessive. Ideas already have community_id
        Schema::table('idea_user', function(Blueprint $table) {
            $table->bigInteger('community_id')->unsigned()->nullable();
            
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
        Schema::table('idea_user', function(Blueprint $table) {
            $table->dropForeign(['community_id']);
            $table->dropColumn('community_id');
        });  
    }
}
