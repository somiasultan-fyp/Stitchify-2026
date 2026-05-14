<?php
// database/migrations/xxxx_create_portfolios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            
            // tailor se link
            $table->foreignId('tailor_id')->constrained('tailors')->onDelete('cascade');
            
            // portfolio item ki info
            $table->string('image_path');                    // image kahan save hai
            $table->string('title')->nullable();             // e.g., "Bridal Dress"
            $table->text('description')->nullable();         // kaam ka description
            $table->string('category')->nullable();          // e.g., "Bridal, Casual, Formal"
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};