<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisabledNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_notifications', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('disabled_user_id')->unsigned();
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');        
            $table->foreign('disabled_user_id')->references('id')
                    ->on('users')->onDelete('cascade');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disabled_notifications');
    }
}
