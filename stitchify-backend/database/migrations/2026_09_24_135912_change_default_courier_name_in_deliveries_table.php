<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
             $table->string('courier_name')
                  ->default('Tailor Arranged Delivery')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
                $table->string('courier_name')
                    ->default('Courier')
                    ->change();
        });
    }
};
