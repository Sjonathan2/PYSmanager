<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'chest_width' => $this->faker->randomFloat(2, 40, 60),
            'shoulder_width' => $this->faker->randomFloat(2, 35, 55),
            'arm_length' => $this->faker->randomFloat(2, 50, 70),
            'body_length' => $this->faker->randomFloat(2, 55, 80),
            'belly_circumference' => $this->faker->randomFloat(2, 70, 110),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
