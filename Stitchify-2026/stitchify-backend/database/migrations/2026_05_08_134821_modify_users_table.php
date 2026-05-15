<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->string('profile_image')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('profile_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'tailor') NOT NULL");
            
            $table->dropColumn(['profile_image', 'is_active']);
        });
    }
};