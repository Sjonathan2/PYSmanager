<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('custom_color')->nullable();
            
            // Status alur produksi jaket kulit
            $table->enum('status', ['pending', 'cutting', 'sewing', 'finishing', 'ready', 'delivered'])->default('pending');
            
            $table->date('order_date');
            $table->date('deadline_date')->nullable();
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('down_payment', 15, 2)->default(0); // DP
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};