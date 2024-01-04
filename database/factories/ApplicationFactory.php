<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id' => Job::factory(),
            'additional_info' => fake()->sentence(3),
            'address' => fake()->address(),
            'date_created' => now(),
            'educational_attaintment' => fake()->sentence(),
            'latitude' => fake()->latitude(-90, 90),
            'longitude' => fake()->longitude(-180, 180),
            'license_url' => fake()->url(),
            'profile_url' => fake()->url(),
            'status' => fake()->randomElement(ApplicationStatusEnum::class),
        ];
    }
}
