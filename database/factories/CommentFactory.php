<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'author_name' => $this->faker->name(),
            'rating' => $this->faker->numberBetween(3, 5),
            'content' => $this->faker->sentence(10),
            'approved' => $this->faker->boolean(70),
        ];
    }
}
