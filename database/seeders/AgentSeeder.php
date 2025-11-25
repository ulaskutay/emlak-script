<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agentUsers = User::where('role', 'agent')->get();

        foreach ($agentUsers as $user) {
            Agent::create([
                'user_id' => $user->id,
                'phone' => fake()->phoneNumber(),
                'whatsapp' => fake()->phoneNumber(),
                'bio' => fake()->paragraph(),
                'active_listings_count' => 0,
            ]);
        }
    }
}

