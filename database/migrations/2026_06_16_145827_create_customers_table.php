<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Detail ukuran khusus jaket kulit (Custom Measurements)
            $table->decimal('chest_width', 5, 2)->nullable()->comment('Lebar Dada');
            $table->decimal('shoulder_width', 5, 2)->nullable()->comment('Lebar Bahu');
            $table->decimal('arm_length', 5, 2)->nullable()->comment('Panjang Lengan');
            $table->decimal('body_length', 5, 2)->nullable()->comment('Panjang Badan');
            $table->decimal('belly_circumference', 5, 2)->nullable()->comment('Lingkar Perut');
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};