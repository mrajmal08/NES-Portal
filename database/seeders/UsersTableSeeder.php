<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'  => 'Admin',
                'email'=> 'admin@gmail.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'role_id' => 1,
                'created_at' => NOW(),
            ]
        ]);
    }
}
