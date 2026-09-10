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
            
            $table->string('order_number')->unique(); 
            
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('tailor_id')->constrained('tailors')->onDelete('cascade');
            
            $table->string('dress_type');                    
            $table->text('special_instructions')->nullable(); 
            $table->string('fabric_provided_by');           
            $table->string('fabric_details')->nullable();    
            
            $table->decimal('price', 8, 2)->nullable();     
            $table->decimal('advance_paid', 8, 2)->default(0); 
            
            // dates
            $table->date('expected_delivery_date')->nullable(); 
            $table->date('actual_delivery_date')->nullable();  
            
            $table->enum('status', [
                'pending',      
                'accepted',   
                'in_progress',  
                'ready',        
                'dispatched', 
                'delivered',    
                'cancelled'    
            ])->default('pending');
            
            $table->enum('payment_status', [
                'unpaid',
                'advance_paid',
                'fully_paid'
            ])->default('unpaid');
            
            $table->enum('delivery_type', ['pickup', 'home_delivery'])->default('pickup');
          
            $table->string('tracking_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};