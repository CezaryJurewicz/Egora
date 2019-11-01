<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTypeVerified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_types', function(Blueprint $table) {
            $table->set('class',['guest', 'user', 'member', 'petitioner', 'officer'])->default('guest')->after('subtitle');
            $table->boolean('verified')->default(0)->after('class');
            $table->boolean('candidate')->default(0)->after('verified');
            $table->boolean('fake')->default(0)->after('candidate');
        });
        
        Artisan::call('db:seed', [
            '--class' => UserTypes::class,
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
            $table->dropColumn('class');
            $table->dropColumn('verified');
            $table->dropColumn('candidate');
            $table->dropColumn('fake');
        });
    }
}
