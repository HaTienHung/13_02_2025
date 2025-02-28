<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
