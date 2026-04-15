<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();   // duplicate email allow nahi hogi
            $table->string('phone');
            $table->string('password');          // hashed password store hoga
            $table->enum('role', ['customer', 'tailor']);
            // tailor ke liye extra fields (nullable kyunki customer ke liye zaroorat nahi)
            $table->text('address')->nullable();
            $table->string('category')->nullable();
            $table->integer('slot_capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
