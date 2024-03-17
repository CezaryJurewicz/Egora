<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->smallInteger('score')->default(0);
            
            $table->bigInteger('idea_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('idea_id')->references('id')
                    ->on('ideas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_ratings');
    }
}
