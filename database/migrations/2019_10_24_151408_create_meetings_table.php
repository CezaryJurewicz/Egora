<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            
            $table->dateTime('start_at');
            $table->bigInteger('city_id')->unsigned();
            $table->text('address');

            
            $table->string('topic');
            $table->text('comments')->nullable();            
            
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
            
            $table->foreign('city_id')->references('id')
                    ->on('cities')->onDelete('cascade');
        });
        
        Schema::create('meeting_users', function (Blueprint $table) {
            $table->bigInteger('meeting_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('meeting_id')->references('id')
                    ->on('meetings')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')
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
        Schema::dropIfExists('meeting_users');
        Schema::dropIfExists('meetings');
    }
}
