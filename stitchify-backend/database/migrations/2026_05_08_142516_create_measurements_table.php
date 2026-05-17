<?php
// database/migrations/xxxx_create_measurements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            
            // order se link — ek order ki ek measurement hogi
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // basic measurements (inches mein)
            $table->decimal('chest', 5, 2)->nullable();
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hips', 5, 2)->nullable();
            $table->decimal('shoulder', 5, 2)->nullable();
            $table->decimal('sleeve_length', 5, 2)->nullable();
            $table->decimal('shirt_length', 5, 2)->nullable();
            $table->decimal('trouser_length', 5, 2)->nullable();
            $table->decimal('trouser_waist', 5, 2)->nullable();
            $table->decimal('neck', 5, 2)->nullable();
            
            // extra notes
            $table->text('additional_notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};