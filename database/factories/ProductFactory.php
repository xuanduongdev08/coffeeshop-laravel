<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $price = $this->faker->numberBetween(25000, 200000);

        return [
            'category_id'    => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name'           => $this->faker->words(3, true),
            'description'    => $this->faker->paragraph(),
            'price'          => $price,
            'discount_price' => $this->faker->boolean(30) ? $price * 0.9 : null,
            'image'          => null,
            'stock'          => $this->faker->numberBetween(0, 100),
            'is_active'      => true,
            'is_featured'    => $this->faker->boolean(20),
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }
}
