<?php

use Illuminate\Database\Seeder;

class UserTypeVerified extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')
              ->update(['verified' => 1]);
        
        DB::table('user_types')
              ->where('title', 'like', 'Unverified%')
              ->update(['verified' => 0]);
    }
}
