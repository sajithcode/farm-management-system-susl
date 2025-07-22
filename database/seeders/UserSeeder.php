<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Farm Admin',
            'email' => 'admin@farm.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'location' => 'Main Office',
        ]);

        // Create Data Collector User
        User::create([
            'name' => 'John Data Collector',
            'email' => 'datacollector@farm.com',
            'password' => Hash::make('password123'),
            'role' => 'data_collector',
            'location' => 'Farm A - Section 1',
        ]);

        // Create VC User
        User::create([
            'name' => 'Dr. Sarah Veterinarian',
            'email' => 'vet@farm.com',
            'password' => Hash::make('password123'),
            'role' => 'vc',
            'location' => 'Veterinary Clinic',
        ]);
    }
}
