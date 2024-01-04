<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operator = User::factory()
            ->busOperator()
            ->has(
                Organization::factory()
                ->hasBuses(5)
                ->has(
                    Job::factory(4)
                    ->hasQuestions(2)
                    ->has(
                        Application::factory(2)
                        ->state(new Sequence(fn (Sequence $sequence) => ['user_id' =>  User::factory()->jobseeker()]))
                    )
                )
            )
            ->create();
    }
}
