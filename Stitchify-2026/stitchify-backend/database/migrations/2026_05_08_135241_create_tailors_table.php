<?php
// database/migrations/xxxx_create_tailors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tailors', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('shop_name')->nullable();    
            $table->text('bio')->nullable();              
            $table->string('city')->nullable();          
            $table->string('address')->nullable();     

            $table->integer('experience_years')->default(0); 
            $table->integer('max_slots')->default(5);      
            $table->integer('available_slots')->default(5);
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->string('specialization')->nullable(); 
            
            $table->decimal('base_price', 8, 2)->nullable();
            
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tailors');
    }
};
