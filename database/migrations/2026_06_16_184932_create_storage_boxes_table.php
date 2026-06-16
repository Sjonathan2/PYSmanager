<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->onDelete('cascade'); // Hanya untuk zone type 'container'
            $table->string('name')->nullable(); // e.g. "Box 1"
            $table->string('color')->nullable();
            $table->integer('order_index'); // Urutan tumpukan vertikal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_boxes');
    }
};