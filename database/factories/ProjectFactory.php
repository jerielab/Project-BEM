<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(12),
            'deadline' => $this->faker->dateTimeBetween('now', '+2 months'),
            'status' => $this->faker->randomElement(Project::STATUSES),
        ];
    }
}
