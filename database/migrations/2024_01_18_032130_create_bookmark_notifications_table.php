<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('egora_id')->default(config('egoras.default.id'));
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('receiver_id')->unsigned();
            $table->bigInteger('bookmark_id')->unsigned();
            $table->string('message')->nullable();
            $table->boolean('viewed')->default(false);
                        
            $table->foreign('sender_id')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('bookmark_id')->references('id')
                    ->on('bookmarks')->onDelete('cascade');
            
            $table->softDeletes();
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
        Schema::dropIfExists('bookmark_notifications');
    }
}
