<?php
namespace Database\Factories;

use App\Models\Category;
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
            'title' => $this->faker->words(3, true), // Рандомное название товара
            'price' => $this->faker->randomFloat(2, 1, 1000), // Рандомная цена от 1 до 1000
            'eID' => $this->faker->randomNumber(), // Рандомное значение eID
            // Привязываем товар к случайной категории
            // 'category_id' => Category::inRandomOrder()->first()->id, 
        ];
    }
}
