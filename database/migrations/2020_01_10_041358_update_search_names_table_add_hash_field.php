<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSearchNamesTableAddHashField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_names', function(Blueprint $table) {
            $table->string('hash');
        });      
        
        Artisan::call('db:seed', [
            '--class' => SearchNameHashes::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('search_names', function(Blueprint $table) {
            $table->dropColumn('hash');
        });
    }
}
