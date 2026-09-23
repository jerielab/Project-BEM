<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@projectflow.test',
        ]);

        Project::factory()
            ->count(4)
            ->for($user)
            ->create()
            ->each(function (Project $project) use ($user) {
                Task::factory()
                    ->count(rand(4, 8))
                    ->for($project)
                    ->for($user)
                    ->create();
            });
    }
}
