<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->set('type',['string', 'boolean', 'text', 'number'])->default('string');
            $table->text('value');
            $table->timestamps();
        });
        
        DB::table('settings')->insert([
            'name' => 'verified_at_registration',
            'type' => 'boolean',
            'value' => 1
        ]);
        DB::table('settings')->insert([
            'name' => 'message',
            'type' => 'text',
            'value' => ''
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
