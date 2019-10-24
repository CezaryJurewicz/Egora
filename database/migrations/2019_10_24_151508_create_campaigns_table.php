<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('campaign_users', function (Blueprint $table) {
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('campaign_id')->references('id')
                    ->on('campaigns')->onDelete('cascade');
            
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
        Schema::dropIfExists('campaign_users');
        Schema::dropIfExists('campaigns');
    }
}
