<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $listings = Listing::all();
        $customers = Customer::all();
        $agents = Agent::all();
        $statuses = ['new', 'in_progress', 'closed'];

        // Create inquiries with listings
        foreach ($listings->random(20) as $listing) {
            Inquiry::create([
                'listing_id' => $listing->id,
                'customer_id' => fake()->boolean(70) ? $customers->random()->id : null,
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'email' => fake()->optional()->email(),
                'message' => fake()->sentence(10),
                'status' => fake()->randomElement($statuses),
                'assigned_agent_id' => fake()->boolean(60) ? $agents->random()->id : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        // Create general inquiries without listings
        for ($i = 0; $i < 10; $i++) {
            Inquiry::create([
                'listing_id' => null,
                'customer_id' => fake()->boolean(50) ? $customers->random()->id : null,
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'email' => fake()->optional()->email(),
                'message' => fake()->sentence(10),
                'status' => fake()->randomElement($statuses),
                'assigned_agent_id' => fake()->boolean(40) ? $agents->random()->id : null,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

