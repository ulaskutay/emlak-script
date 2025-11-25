<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Agent;
use App\Models\ListingPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::all();
        $cities = ['İstanbul', 'Ankara', 'İzmir', 'Antalya', 'Bursa', 'Miami', 'New York', 'Chicago', 'Los Angeles', 'Austin'];
        $districts = ['Kadıköy', 'Beşiktaş', 'Şişli', 'Beyoğlu', 'Üsküdar', 'Manhattan', 'Downtown', 'Suburb', 'Center'];
        $types = ['sale', 'rent'];
        $statuses = ['active', 'pending', 'sold', 'rented', 'passive'];
        $currencies = ['TRY', 'USD', 'EUR'];
        $heatingTypes = ['Kombi', 'Merkezi', 'Soba', 'Klima', 'Elektrikli'];

        foreach ($agents as $agent) {
            $listingsCount = rand(5, 15);
            
            for ($i = 0; $i < $listingsCount; $i++) {
                $type = fake()->randomElement($types);
                $status = fake()->randomElement($statuses);
                
                $listing = Listing::create([
                    'agent_id' => $agent->id,
                    'title' => fake()->randomElement([
                        'Modern Villa with Pool',
                        'Downtown Apartment',
                        'Suburban Family Home',
                        'Luxury Penthouse',
                        'Cozy Cottage',
                        'Urban Loft',
                        'Beachfront Condo',
                        'Mountain View House',
                        'Garden Flat',
                        'Studio Apartment',
                    ]),
                    'slug' => null, // Will be auto-generated
                    'description' => fake()->paragraph(5),
                    'type' => $type,
                    'status' => $status,
                    'price' => $type === 'sale' 
                        ? fake()->randomFloat(2, 100000, 5000000)
                        : fake()->randomFloat(2, 500, 10000),
                    'price_period' => $type === 'rent' ? fake()->randomElement(['ay', 'yil', 'tam']) : null,
                    'currency' => fake()->randomElement($currencies),
                    'city' => fake()->randomElement($cities),
                    'district' => fake()->randomElement($districts),
                    'address' => fake()->address(),
                    'area_m2' => fake()->numberBetween(50, 500),
                    'bedrooms' => fake()->numberBetween(1, 5),
                    'bathrooms' => fake()->numberBetween(1, 4),
                    'floor' => fake()->numberBetween(1, 20) . '. Kat',
                    'heating_type' => fake()->randomElement($heatingTypes),
                    'furnished' => fake()->boolean(30),
                    'tags' => fake()->randomElements(['Havuz', 'Balkon', 'Garaj', 'Asansör', 'Güvenlik', 'Otopark', 'Manzara', 'Deniz Kenarı'], rand(2, 5)),
                    'published_at' => $status === 'active' ? now()->subDays(rand(1, 30)) : null,
                ]);

                // Note: Photos will be empty in seeder - users need to upload real photos
                // Creating photo records is skipped to avoid broken image links
            }
        }

        // Update active listings count for all agents
        foreach ($agents as $agent) {
            $agent->updateActiveListingsCount();
        }
    }
}

