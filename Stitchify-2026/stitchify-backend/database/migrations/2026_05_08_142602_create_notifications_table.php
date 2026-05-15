<?php
// database/migrations/xxxx_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // kis user ko notification
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // notification content
            $table->string('title');                         // short title
            $table->text('message');                         // pura message
            $table->string('type')->nullable();              // 'order', 'payment', etc.
            
            // read hua ya nahi
            $table->boolean('is_read')->default(false);
            
            // link — click karne par kahan jaye
            $table->string('action_url')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};