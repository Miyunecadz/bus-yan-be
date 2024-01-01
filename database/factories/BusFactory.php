<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'id_number' => rand(100, 500),
            'bus_code' => rand(1, 20),
            'start_point' => fake()->streetAddress(),
            'end_point' => fake()->streetAddress(),
            'plate_number' => rand(10000, 50000)
        ];
    }
}
