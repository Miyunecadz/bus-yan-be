<?php

namespace Database\Factories;

use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    public function admin()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => 'admin',
                'is_verified' => true
            ]));
    }

    public function busOperator()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => 'bus-operator',
                'is_verified' => true
            ]));
    }

    public function jobseeker()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => 'jobseeker',
                'is_verified' => true
            ]));
    }
}
