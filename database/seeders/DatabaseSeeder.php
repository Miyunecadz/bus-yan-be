<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\EmployeeStatusEnum;
use App\Models\Bus;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Organization;
use App\Models\Question;
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
        $this->createJobSeeker();

        $this->call([
            ApplicationSeeder::class,
        ]);
    }

    private function createBusOperator()
    {
        $user = User::factory()->busOperator()->create([
            'email' => 'bus.operator@busyan.com',
            'phone_number' => fake()->unique()->phoneNumber(),
            'display_name' => 'Bus Operator',
        ]);

        $organization = Organization::create([
            'owner_user_id' => $user->id,
            'company_name' => 'Cebu Bus',
            'company_address' => 'Cebu',
            'company_description' => 'The Best Bus Provider in Cebu'
        ]);

        $bus = Bus::factory(5)->create(['organization_id' => $organization->id]);
        $job = Job::factory()->create(['organization_id' => $organization->id]);
        Question::factory(2)->create(['job_id' => $job->id]);

        Employee::factory()->create([
            'employee_type' => EmployeeStatusEnum::ASSISTANT_MANAGER,
            'employee_id' => null
        ]);

        $this->createDriver($organization->id);
    }

    private function createDriver($organizationId)
    {
        $user = User::factory()->driver()->create();

        Employee::factory()->create([
            'employee_type' => EmployeeStatusEnum::BUS_DRIVER,
            'employee_id' => $user->id,
            'organization_id' => $organizationId
        ]);
    }

    private function createJobSeeker()
    {
        $user = User::factory()->jobseeker()->create([
            'email' => 'jobseeker@busyan.com'
        ]);
    }
}
