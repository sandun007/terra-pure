<?php
// database/migrations/2026_03_02_000001_add_missing_columns_to_wholesaler_orders.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            // Add customer_phone_2 if not exists
            if (!Schema::hasColumn('wholesaler_orders', 'customer_phone_2')) {
                $table->string('customer_phone_2')->nullable()->after('customer_phone');
            }

            // Add product_description if not exists
            if (!Schema::hasColumn('wholesaler_orders', 'product_description')) {
                $table->text('product_description')->nullable()->after('product_name');
            }

            // Add product_category if not exists
            if (!Schema::hasColumn('wholesaler_orders', 'product_category')) {
                $table->string('product_category')->nullable()->after('product_description');
            }

            // Ensure all necessary columns exist
            if (!Schema::hasColumn('wholesaler_orders', 'product_code')) {
                $table->string('product_code')->nullable()->after('product_id');
            }

            if (!Schema::hasColumn('wholesaler_orders', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_price');
            }

            if (!Schema::hasColumn('wholesaler_orders', 'call_status')) {
                $table->string('call_status')->default('Pending Call')->after('platform');
            }

            if (!Schema::hasColumn('wholesaler_orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            $columns = [
                'customer_phone_2',
                'product_description',
                'product_category',
                'product_code',
                'product_image',
                'call_status',
                'payment_status'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('wholesaler_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
