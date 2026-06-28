<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            // Order se link
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            // Tracking ID — Showing to customer 
            $table->string('tracking_id')->unique();

            // Delivery type
            $table->enum('type', ['home_delivery', 'pickup']);

            // Current status
            $table->enum('status', [
                'scheduled',              // Delivery booking  
                'picked_up_from_customer',// Courier pickup fabric from customer
                'delivered_to_tailor',    // Delivered to Tailor 
                'stitching_in_progress',  
                'picked_up_from_tailor',  // Courier picked from Tailor
                'out_for_delivery',       // Order out for delivery to Customer
                'delivered',              // Delivered to Customer
            ])->default('scheduled');

            // Courier details
            $table->string('courier_name')->default('Leopards Courier');
            $table->string('courier_tracking_ref')->nullable();

            // Addresses
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();

            // Estimated delivery date
            $table->date('estimated_date')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
