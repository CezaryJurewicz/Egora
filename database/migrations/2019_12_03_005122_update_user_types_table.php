<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_types', function(Blueprint $table) {
            $table->boolean('former')->default(0)->after('fake');
        });
        
        Artisan::call('db:seed', [
            '--class' => FormerUserTypes::class,
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_types', function(Blueprint $table) {
            $table->dropColumn('former');
        });
    }
}
