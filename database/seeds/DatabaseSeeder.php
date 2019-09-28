<?php

use Illuminate\Database\Seeder;

use App\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.net',
            'password' => Hash::make('admin'),
            'api_token'=> 'b2072c8d0bbce10ac85f7bede7131a2beaae55ab',
        ]);
    }
}
