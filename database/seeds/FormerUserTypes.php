<?php

use Illuminate\Database\Seeder;

class FormerUserTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')
            ->where('title', 'like', '%Former%')
            ->update(['former' => 1]);
    }
}
