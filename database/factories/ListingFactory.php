<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Level 75 Old Account + 5 Evo Gun Skins',
            'Season 2 Hip Hop Bundle Account',
            'Full Max Sakura Bundle Free Fire ID',
            'Grandmaster Ranked High KD Free Fire Account',
            'Rare Angelic Pants + Criminal Bundle Account',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 9999),
            'uid' => (string) fake()->unique()->numberBetween(100000000, 999999999),
            'level' => fake()->numberBetween(30, 85),
            'account_age' => fake()->randomElement(['1 Year', '2 Years', '3 Years', 'Since Season 1', 'Since 2019']),
            'price' => fake()->randomFloat(2, 500, 25000),
            'description' => fake()->paragraph(),
            'character_count' => fake()->numberBetween(15, 60),
            'gun_skin_count' => fake()->numberBetween(20, 150),
            'elite_pass_count' => fake()->numberBetween(5, 35),
            'rare_item_count' => fake()->numberBetween(1, 20),
            'status' => 'available',
            'is_featured' => fake()->boolean(20),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'sold_at' => null,
        ];
    }

    /**
     * Indicate that the listing is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'sold_at' => null,
        ]);
    }

    /**
     * Indicate that the listing is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'sold_at' => now(),
        ]);
    }

    /**
     * Indicate that the listing is hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hidden',
        ]);
    }

    /**
     * Indicate that the listing is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
