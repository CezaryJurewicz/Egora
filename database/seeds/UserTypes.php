<?php

use Illuminate\Database\Seeder;

class UserTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        DB::table('user_types')->insertOrIgnore([[
            'id' => 1,
            'title' => 'Unverified User',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 2,
            'title' => 'Unverified ILP Member',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 3,
            'title' => 'Verified User',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 4,
            'title' => 'Verified ILP Member',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 5,
            'title' => 'Verified ILP Member Candidate',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 6,
            'title' => 'ILP Petitioner',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 7,
            'title' => 'ILP Petitioner Candidate',
            'subtitle' => '',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 8,
            'title' => 'ILP Officer',
            'subtitle' => 'Filosofos tou Dromou',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 9,
            'title' => 'ILP Officer Candidate',
            'subtitle' => 'Filosofos tou Dromou',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 10,
            'title' => 'Fake ILP Officer',
            'subtitle' => 'Filosofos tou Dromou',
            'created_at' => $now,
            'updated_at' => $now
        ],[
            'id' => 11,
            'title' => 'Fake ILP Officer Candidate',
            'subtitle' => 'Filosofos tou Dromou',
            'created_at' => $now,
            'updated_at' => $now
        ]]);
        
        DB::table('user_types')
              ->where('title', 'like', 'Verified%')
              ->update(['verified' => 1]);
        
        DB::table('user_types')
              ->where('title', 'like', 'Fake%')
              ->update(['fake' => 1]);
        
        DB::table('user_types')
              ->where('title', 'like', '%Candidate%')
              ->update(['candidate' => 1]);
        
        $classes = ['user' => 'User', 'member' => 'Member', 'petitioner' => 'Petitioner', 'officer' => 'Officer'];
        
        foreach ($classes as $class => $string) {        
            DB::table('user_types')
                  ->where('title', 'like', '%'.$string.'%')
                  ->update(['class' => $class]);
        }
    }
}
