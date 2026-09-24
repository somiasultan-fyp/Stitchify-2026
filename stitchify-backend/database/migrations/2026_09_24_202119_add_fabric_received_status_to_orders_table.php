<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'fabric_received',
            'in_progress',
            'ready',
            'dispatched',
            'on_the_way',
            'delivered',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'fabric_received')
            ->update(['status' => 'accepted']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'accepted',
            'in_progress',
            'ready',
            'dispatched',
            'on_the_way',
            'delivered',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};