<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->smallInteger('position')->unsigned(true)->default(0);
            $table->smallInteger('order')->nullable();
            
            $table->bigInteger('idea_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('community_id')->unsigned()->nullable();
            
            $table->foreign('idea_id')->references('id')
                    ->on('ideas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('bookmarks');
    }
}
