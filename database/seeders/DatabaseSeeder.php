<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // admin user
        User::factory()->admin()->create([
            'email' => 'admin@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'System Admin',
        ]);

        $this->createBusOperator();
    }

    private function createBusOperator()
    {
        $user = User::factory()->busOperator()->create([
            'email' => 'bus.operator@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'Bus Operator',
        ]);

        Organization::create([
            'owner_user_id' => $user->id,
            'company_name' => 'Cebu Bus',
            'company_address' => 'Cebu',
            'company_description' => 'The Best Bus Provider in Cebu'
        ]);
    }
}
