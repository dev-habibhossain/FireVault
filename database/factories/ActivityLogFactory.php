<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actor_id' => User::factory(),
            'action' => fake()->randomElement([
                'listing.created',
                'listing.updated',
                'listing.reassigned',
                'listing.status_changed',
                'sale.created',
                'sale.price_corrected',
                'moderator.created',
                'moderator.activated',
                'moderator.deactivated',
                'auth.login',
                'auth.logout',
            ]),
            'subject_type' => null,
            'subject_id' => null,
            'description' => fake()->sentence(6),
            'properties' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => now(),
        ];
    }
}
