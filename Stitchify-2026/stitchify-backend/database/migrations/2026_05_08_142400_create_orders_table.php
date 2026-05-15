<?php
// database/migrations/xxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // unique order number — customer ko dikhayenge
            $table->string('order_number')->unique(); // e.g., ORD-2024-0001
            
            // kaun ne order diya, kis tailor ko
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('tailor_id')->constrained('tailors')->onDelete('cascade');
            
            // order ki details
            $table->string('dress_type');                    // e.g., "Shalwar Kameez, Bridal"
            $table->text('special_instructions')->nullable(); // koi khaas baat
            $table->string('fabric_provided_by');            // 'customer' ya 'tailor'
            $table->string('fabric_details')->nullable();    // fabric ka description
            
            // pricing
            $table->decimal('price', 8, 2)->nullable();      // tailor ne jo price di
            $table->decimal('advance_paid', 8, 2)->default(0); // advance payment
            
            // dates
            $table->date('expected_delivery_date')->nullable(); // expected delivery
            $table->date('actual_delivery_date')->nullable();   // actual delivery
            
            // order status — yeh order kahan tak pahuncha
            $table->enum('status', [
                'pending',      // order aaya, tailor ne abhi accept nahi kiya
                'accepted',     // tailor ne accept kiya
                'in_progress',  // stitching chal rahi hai
                'ready',        // taiyar ho gaya
                'dispatched',   // delivery ke liye bheja
                'delivered',    // customer tak pahunch gaya
                'cancelled'     // cancel ho gaya
            ])->default('pending');
            
            // payment status
            $table->enum('payment_status', [
                'unpaid',
                'advance_paid',
                'fully_paid'
            ])->default('unpaid');
            
            // delivery type
            $table->enum('delivery_type', ['pickup', 'home_delivery'])->default('pickup');
            
            // tracking ID (Week 4 mein use hoga)
            $table->string('tracking_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};