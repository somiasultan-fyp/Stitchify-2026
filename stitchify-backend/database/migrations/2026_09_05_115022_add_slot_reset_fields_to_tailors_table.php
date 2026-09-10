<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tailors', function (Blueprint $table) {
            $table->integer('base_max_slots')->nullable()->after('max_slots');
            $table->timestamp('last_slot_reset_at')->nullable()->after('base_max_slots');
        });
    }

    public function down()
    {
        Schema::table('tailors', function (Blueprint $table) {
            $table->dropColumn(['base_max_slots', 'last_slot_reset_at']);
        });
    }
};