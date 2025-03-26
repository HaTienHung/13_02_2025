<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin01@gmail.com',
        //     'password' => '12345678',
        //     'role' => 'admin',
        // ]);

        User::create([
            'name' => 'User 02',
            'email' => 'user02@gmail.com',
            'password' => '12345678',
            'role' => 'user',
        ]);
    }
}
