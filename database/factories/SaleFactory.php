<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 500, 20000);
        // Actual price may be slightly lower due to negotiation
        $actualPrice = round($price * fake()->randomFloat(2, 0.85, 1.0), 2);

        return [
            'listing_id' => Listing::factory()->sold(),
            'moderator_id' => User::factory()->moderator(),
            'listed_price' => $price,
            'actual_price' => $actualPrice,
            'sold_at' => now(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
