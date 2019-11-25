<?php

use Illuminate\Database\Seeder;

class MoreUserTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        DB::table('user_types')->insertOrIgnore([
            [
                'title' => 'Unverified ILP Member Candidate',
                'subtitle' => '',
                'class' => 'member',
                'verified' => 0,
                'candidate' => 1,
                'fake' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'Unverified Former Member',
                'subtitle' => '',
                'class' => 'member',
                'verified' => 0,
                'candidate' => 0,
                'fake' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'Verified Former Member',
                'subtitle' => '',
                'class' => 'member',
                'verified' => 0,
                'candidate' => 0,
                'fake' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);
    }
}
