<?php
// database/migrations/xxxx_create_complaints_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            
            // kaun ne complaint ki
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // complaint detail
            $table->string('subject');
            $table->text('message');
            
            // admin ka jawab
            $table->text('admin_response')->nullable();
            
            // status
            $table->enum('status', ['open', 'in_review', 'resolved', 'closed'])->default('open');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};