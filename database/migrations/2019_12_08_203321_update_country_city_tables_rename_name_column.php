<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCountryCityTablesRenameNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('cities', function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('cities', function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
    }
}
