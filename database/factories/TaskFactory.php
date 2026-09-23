<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->sentence(4)),
            'description' => $this->faker->sentence(10),
            'deadline' => $this->faker->dateTimeBetween('-5 days', '+1 month'),
            'status' => $this->faker->randomElement(Task::STATUSES),
            'position' => $this->faker->numberBetween(0, 10),
        ];
    }
}
