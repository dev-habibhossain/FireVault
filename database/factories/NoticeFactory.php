<?php

namespace Database\Factories;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notice>
 */
class NoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => User::factory()->admin(),
            'title' => fake()->sentence(5),
            'body' => fake()->paragraphs(2, true),
            'priority' => 'normal',
            'audience_type' => 'all',
            'expires_at' => null,
        ];
    }

    /**
     * Indicate that the notice is important.
     */
    public function important(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'important',
        ]);
    }

    /**
     * Indicate that the notice has an expiration date.
     */
    public function expiring(?\DateTimeInterface $date = null): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => $date ?? now()->addDays(7),
        ]);
    }
}
