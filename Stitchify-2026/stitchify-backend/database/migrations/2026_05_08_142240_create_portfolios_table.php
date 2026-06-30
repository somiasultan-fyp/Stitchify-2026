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
            
            $table->foreignId('tailor_id')->constrained('tailors')->onDelete('cascade');
            
            $table->string('image_path');                    
            $table->string('title')->nullable();             
            $table->text('description')->nullable();         
            $table->string('category')->nullable();        
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};