<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdeasAddIdeaIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ideas', function(Blueprint $table) {
            $table->bigInteger('idea_id')->unsigned()->nullable();
            
            $table->foreign('idea_id')->references('id')
                    ->on('ideas')->onDelete('cascade');
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
            $table->dropForeign(['idea_id']);
            $table->dropColumn('idea_id');
        });
    }
}
