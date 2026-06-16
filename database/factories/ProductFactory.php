<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name . ' ' . Str::random(4)),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['Biker Jacket', 'Bomber', 'Blazer', 'Vest']),
        ];
    }
}
