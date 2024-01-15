<?php

namespace Database\Factories;

use App\Enums\EmployeeStatusEnum;
use App\Models\Operator;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
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
            'employee_id' => User::factory(),
            'operator_id' => Operator::factory(),
            'id_number' => rand(1000, 9999),
            'full_name' => fake()->name(),
            'email' => fake()->email(),
            'contact_number' => fake()->phoneNumber(),
            'employee_type' => Arr::random(EmployeeStatusEnum::cases())
        ];
    }
}
