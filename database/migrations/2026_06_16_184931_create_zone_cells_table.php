<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zone_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->integer('x'); // Coordinate X di grid
            $table->integer('y'); // Coordinate Y di grid
            $table->timestamps();

            // Memastikan satu kotak grid hanya bisa diisi 1 zone
            $table->unique(['x', 'y']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_cells');
    }
};