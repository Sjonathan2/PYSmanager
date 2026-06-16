<?php

namespace Database\Factories;

use App\Models\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductionOrderFactory extends Factory
{
    protected $model = ProductionOrder::class;

    public function definition(): array
    {
        return [
            'po_number' => 'PO-' . strtoupper(Str::random(4)) . '-' . $this->faker->numerify('###'),
            'custom_color' => $this->faker->randomElement(['Black', 'Dark Brown', 'Tan', 'Navy', 'Maroon', null]),
            'status' => $this->faker->randomElement(['pending', 'cutting', 'sewing', 'finishing', 'ready', 'delivered']),
            'order_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'deadline_date' => $this->faker->dateTimeBetween('+1 weeks', '+2 months'),
            'total_price' => $this->faker->numberBetween(500, 3000) * 1000,
            'down_payment' => $this->faker->numberBetween(100, 1000) * 1000,
            'notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }
}
