<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ProductionOrder;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 products, each with 3 variants + 1 low stock
        Product::factory(10)->create()->each(function ($product) {
            ProductVariant::factory(3)->create([
                'product_id' => $product->id,
            ]);
            
            ProductVariant::factory()->create([
                'product_id' => $product->id,
                'stock' => rand(1, 4),
            ]);
        });

        // Create transactions spanning 6 months
        Transaction::factory(80)->create();

        // Create customers
        Customer::factory(8)->create();

        // Create suppliers
        Supplier::factory(6)->create();

        // Create production orders
        $customers = Customer::all();
        $products = Product::all();
        $statuses = ['pending', 'cutting', 'sewing', 'finishing', 'ready', 'delivered'];
        
        for ($i = 0; $i < 10; $i++) {
            ProductionOrder::factory()->create([
                'customer_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'status' => $statuses[array_rand($statuses)],
                'order_date' => Carbon::now()->subDays(rand(0, 60)),
                'deadline_date' => Carbon::now()->addDays(rand(7, 45)),
            ]);
        }
    }
}
