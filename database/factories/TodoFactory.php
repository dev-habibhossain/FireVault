<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assigned_to' => User::factory()->moderator(),
            'created_by' => User::factory()->admin(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => 'open',
            'due_date' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
            'completed_at' => null,
            'completed_by' => null,
        ];
    }

    /**
     * Indicate that the todo is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => $attributes['assigned_to'] ?? User::factory(),
        ]);
    }

    /**
     * Indicate that the todo is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
            'due_date' => now()->subDays(2),
        ]);
    }
}
