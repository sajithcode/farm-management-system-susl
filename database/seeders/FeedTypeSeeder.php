<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeedType;

class FeedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feedTypes = [
            [
                'name' => 'Starter Feed',
                'description' => 'High protein feed for young animals',
                'unit' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Grower Feed',
                'description' => 'Balanced nutrition for growing animals',
                'unit' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Finisher Feed',
                'description' => 'High energy feed for finishing animals',
                'unit' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Hay',
                'description' => 'Natural grass feed for cattle',
                'unit' => 'bales',
                'is_active' => true,
            ],
            [
                'name' => 'Corn Silage',
                'description' => 'Fermented corn feed',
                'unit' => 'tons',
                'is_active' => true,
            ],
            [
                'name' => 'Mineral Mix',
                'description' => 'Essential vitamins and minerals supplement',
                'unit' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Layer Feed',
                'description' => 'Specialized feed for laying hens',
                'unit' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Broiler Feed',
                'description' => 'High protein feed for meat chickens',
                'unit' => 'kg',
                'is_active' => true,
            ],
        ];

        foreach ($feedTypes as $feedType) {
            FeedType::create($feedType);
        }
    }
}
