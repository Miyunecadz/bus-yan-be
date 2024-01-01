<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
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
            'title' => fake()->title(),
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'salary' => rand(1000, 10000),
            'job_highlights' => fake()->text(),
            'qualifications' => fake()->text(),
            'how_to_apply' => fake()->text(),
            'about_the_company' => fake()->text()
        ];
    }
}
