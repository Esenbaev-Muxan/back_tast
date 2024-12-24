<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']) . '-' . $this->faker->unique()->numberBetween(1, 10000);
            },
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'eID' => $this->faker->uuid,
        ];
    }
}
