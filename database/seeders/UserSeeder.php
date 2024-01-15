<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Operator;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCooperativeOrganizationUsers();
    }

    private function createCooperativeOrganizationUsers()
    {
        $userBusCooperative = User::factory()->busCooperative()->create([
            'email' => '1coop.cooperative@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'Bus Cooperative',
        ]);

        $userBusOperator = User::factory()->busOperator()->create([
            'email' => '1coop.operator@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'Bus Operator',
        ]);

        $userEmployeeDriver = User::factory()->create([
            'email' => '1coop.driver@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'Bus Driver',
        ]);

        Organization::factory()
            ->for($userBusCooperative)
            ->has(Operator::factory()
                ->state(function () use ($userBusOperator) {
                    return [
                        'user_id' => $userBusOperator->id
                    ];
                })
                ->has(Employee::factory()
                    ->state(function (array $attributes, Operator $operator) use ($userEmployeeDriver) {
                    return [
                        'organization_id' => $operator->organization_id,
                        'employee_id' => $userEmployeeDriver->id
                    ];
                })
            ))
            ->hasBuses(1)
            ->create([
                'company_name' => '1Cooperative company'
            ]);
    }
}
