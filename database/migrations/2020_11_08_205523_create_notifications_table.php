<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_presets', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('receiver_id')->unsigned();
            $table->bigInteger('notification_id')->nullable()->unsigned();
            $table->bigInteger('notification_preset_id')->nullable()->unsigned();
            $table->bigInteger('idea_id')->nullable()->unsigned();
            $table->boolean('viewed')->default(false);
                        
            $table->foreign('sender_id')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->foreign('notification_id')->references('id')
                    ->on('notifications');
            $table->foreign('notification_preset_id')->references('id')
                    ->on('notification_presets')->onDelete('cascade');
            $table->foreign('idea_id')->references('id')
                    ->on('ideas')->onDelete('cascade');
            
            $table->timestamps();
        });
        
        Artisan::call('db:seed', [
            '--class' => NotificationsPreset::class,
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_presets');
    }
}
