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
            
            // users table se link — tailor delete ho to yeh bhi delete ho
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // tailor ki basic info
            $table->string('shop_name')->nullable();        // dukan ka naam
            $table->text('bio')->nullable();                // apne baare mein
            $table->string('city')->nullable();             // shehar
            $table->string('address')->nullable();          // pura address
            $table->integer('experience_years')->default(0); // kitne saal ka tajurba
            
            // slots — maximum kitne orders ek waqt mein le sakta hai
            $table->integer('max_slots')->default(5);       // total capacity
            $table->integer('available_slots')->default(5); // abhi kitne baaki hain
            
            // status — approved hai ya nahi (admin approve karega)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // specialization — kya karta hai
            $table->string('specialization')->nullable(); // e.g., "Bridal, Casual"
            
            // pricing
            $table->decimal('base_price', 8, 2)->nullable(); // minimum price
            
            $table->timestamps(); // created_at, updated_at automatic
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tailors');
    }
};
