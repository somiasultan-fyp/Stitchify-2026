<?php
// database/migrations/xxxx_create_customers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // users table se link
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // customer ki extra info
            $table->string('address')->nullable();           // delivery address
            $table->string('city')->nullable();              // shehar
            $table->date('date_of_birth')->nullable();       // date of birth
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
