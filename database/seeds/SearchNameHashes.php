<?php

use Illuminate\Database\Seeder;
use App\SearchName;
use Illuminate\Support\Facades\Hash;

class SearchNameHashes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = SearchName::get();
        
        foreach ($names as $name) {        
            $name->hash = base64_encode(Hash::make($name->name));
            $name->save();
        }        
    }
}
