<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('wholesaler_orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('order_date');
            }

            if (!Schema::hasColumn('wholesaler_orders', 'return_date')) {
                $table->date('return_date')->nullable()->after('delivery_date');
            }

            if (!Schema::hasColumn('wholesaler_orders', 'rescheduling_date')) {
                $table->date('rescheduling_date')->nullable()->after('return_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'return_date', 'rescheduling_date']);
        });
    }
};
