<?php

namespace Database\Factories;

use App\Enums\UserAccountEnum;
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
                'account_role' => UserAccountEnum::ADMIN->value,
                'is_verified' => true
            ]));
    }

    public function busOperator()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => UserAccountEnum::BUS_OPERATOR->value,
                'is_verified' => true
            ]));
    }

    public function driver()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => UserAccountEnum::DRIVER->value,
                'is_verified' => true
            ]));
    }

    public function jobseeker()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => UserAccountEnum::JOBSEEKER->value,
                'is_verified' => true
            ]));
    }

    public function busCooperative()
    {
        return $this->has(UserAccount::factory()
            ->state([
                'account_role' => UserAccountEnum::BUS_COOPERATIVE->value,
                'is_verified' => true
            ]));
    }
}
