<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('campaign_users');
        
        \DB::table('campaigns')->truncate();
        
        Schema::table('campaigns', function(Blueprint $table) {
            $table->dropSoftDeletes();
            
            $table->bigInteger('user_id')->unsigned();
            
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
        Schema::create('campaign_users', function (Blueprint $table) {
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            
            $table->foreign('campaign_id')->references('id')
                    ->on('campaigns')->onDelete('cascade');
            
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
        
        Schema::table('campaigns', function(Blueprint $table) {
            $table->dropColumn('user_id');
            $table->softDeletes();
        });
    }
}
