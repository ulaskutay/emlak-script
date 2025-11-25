<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@estateflow.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample agent users
        $agentUsers = User::factory(5)->create([
            'role' => 'agent',
        ]);
    }
}

