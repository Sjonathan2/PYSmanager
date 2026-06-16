<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_box_id')->constrained()->onDelete('cascade'); // Di tumpukan mana stok ditaruh
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade'); // Barang apa yang ditaruh
            $table->integer('quantity')->default(1); // Jumlah barang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_placements');
    }
};