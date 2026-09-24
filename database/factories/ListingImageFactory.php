<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListingImage>
 */
class ListingImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'listing_id' => Listing::factory(),
            'image_path' => 'listings/screenshots/'.fake()->uuid().'.webp',
            'sort_order' => 0,
            'is_cover' => false,
        ];
    }

    /**
     * Indicate that this is a cover image.
     */
    public function cover(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_cover' => true,
            'sort_order' => 0,
        ]);
    }
}
