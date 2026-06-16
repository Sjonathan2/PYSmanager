<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => 'SKU-' . strtoupper($this->faker->lexify('????')) . '-' . $this->faker->numerify('####'),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'Custom']),
            'color' => $this->faker->randomElement(['Black', 'Dark Brown', 'Tan', 'Navy']),
            'stock' => $this->faker->numberBetween(1, 50),
            'price' => $this->faker->numberBetween(500, 2000) * 1000,
        ];
    }
}
