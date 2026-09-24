<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('currency');
            $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_rate');
            $table->decimal('tailor_amount', 10, 2)->nullable()->after('commission_amount');
            $table->enum('payout_status', ['held', 'payable', 'paid'])->default('held')->after('payment_type');
            $table->timestamp('released_at')->nullable()->after('payout_status');
            $table->timestamp('paid_out_at')->nullable()->after('released_at');
        });

        $rate = (float) config('stitchify.commission_rate', 4);

        DB::table('payments')
            ->where('status', 'completed')
            ->update([
                'commission_rate'   => $rate,
                'commission_amount' => DB::raw("ROUND(amount * {$rate} / 100, 2)"),
                'tailor_amount'     => DB::raw("ROUND(amount - ROUND(amount * {$rate} / 100, 2), 2)"),
            ]);

        DB::statement("UPDATE payments p
            JOIN orders o ON o.id = p.order_id
            SET p.payout_status = 'payable', p.released_at = NOW()
            WHERE p.status = 'completed' AND o.status = 'delivered'");
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'commission_rate',
                'commission_amount',
                'tailor_amount',
                'payout_status',
                'released_at',
                'paid_out_at',
            ]);
        });
    }
};