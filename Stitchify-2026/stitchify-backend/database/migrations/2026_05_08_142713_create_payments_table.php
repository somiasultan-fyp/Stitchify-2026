<?php
// database/migrations/xxxx_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // order se link
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Stripe ka transaction ID
            $table->string('stripe_payment_id')->nullable();
            
            // kitna pay kiya
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('pkr');
            
            // payment ka status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            
            // payment ka type
            $table->enum('payment_type', ['advance', 'full', 'remaining'])->default('full');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};