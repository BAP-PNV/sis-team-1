<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'username' => 'root',
            'password' => '12345678',
            'email' => 'root@gmail.com',
        ]);

        DB::table('folders')->insert([
            'name' => 'root',
            'user_id' => 1,
            'upper_folder_id' => null
        ]);
    }
}
