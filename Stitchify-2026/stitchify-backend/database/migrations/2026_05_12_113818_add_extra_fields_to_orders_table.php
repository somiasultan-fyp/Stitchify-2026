<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('delivery_days')->nullable()->after('expected_delivery_date');
            $table->text('rejection_reason')->nullable()->after('delivery_days');
            $table->timestamp('accepted_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_days', 'rejection_reason', 'accepted_at', 'rejected_at']);
        });
    }
};