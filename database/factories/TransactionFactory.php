<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductVariant;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['income', 'expense']);
        
        return [
            'type' => $type,
            'amount' => $this->faker->numberBetween(100, 5000) * 1000,
            'description' => $type === 'income' ? 'Penjualan via ' . $this->faker->randomElement(['Shopee', 'Tokopedia', 'Offline', 'Instagram', 'WhatsApp']) : 'Biaya ' . $this->faker->randomElement(['Bahan Kulit', 'Listrik', 'Gaji Karyawan', 'Iklan', 'Sewa Tempat', 'Resleting', 'Furing', 'Benang']),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'product_variant_id' => $type === 'income' ? ProductVariant::inRandomOrder()->first()?->id ?? null : null,
            'quantity' => $type === 'income' ? $this->faker->numberBetween(1, 5) : null,
        ];
    }
}
