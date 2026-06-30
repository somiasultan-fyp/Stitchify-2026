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

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            $table->string('tracking_id')->unique();

            $table->enum('type', ['home_delivery', 'pickup']);

            $table->enum('status', [
                'scheduled',              
                'picked_up_from_customer',
                'delivered_to_tailor',  
                'stitching_in_progress',  
                'picked_up_from_tailor', 
                'out_for_delivery',       
                'delivered',         
            ])->default('scheduled');

            $table->string('courier_name')->default('Leopards Courier');
            $table->string('courier_tracking_ref')->nullable();

            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();

            $table->date('estimated_date')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
