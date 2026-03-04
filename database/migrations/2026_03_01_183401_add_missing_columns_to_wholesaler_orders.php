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
            // Add customer_phone_2 if not exists - with conditional positioning
            if (!Schema::hasColumn('wholesaler_orders', 'customer_phone_2')) {
                if (Schema::hasColumn('wholesaler_orders', 'customer_phone')) {
                    $table->string('customer_phone_2')->nullable()->after('customer_phone');
                } else {
                    $table->string('customer_phone_2')->nullable();
                }
            }

            // Add product_description if not exists - with conditional positioning
            if (!Schema::hasColumn('wholesaler_orders', 'product_description')) {
                if (Schema::hasColumn('wholesaler_orders', 'product_name')) {
                    $table->text('product_description')->nullable()->after('product_name');
                } else {
                    $table->text('product_description')->nullable();
                }
            }

            // Add product_category if not exists - with conditional positioning
            if (!Schema::hasColumn('wholesaler_orders', 'product_category')) {
                if (Schema::hasColumn('wholesaler_orders', 'product_description')) {
                    $table->string('product_category')->nullable()->after('product_description');
                } else {
                    $table->string('product_category')->nullable();
                }
            }

            // Ensure all necessary columns exist - with conditional positioning for each
            if (!Schema::hasColumn('wholesaler_orders', 'product_code')) {
                if (Schema::hasColumn('wholesaler_orders', 'product_id')) {
                    $table->string('product_code')->nullable()->after('product_id');
                } else {
                    $table->string('product_code')->nullable();
                }
            }

            if (!Schema::hasColumn('wholesaler_orders', 'product_image')) {
                if (Schema::hasColumn('wholesaler_orders', 'product_price')) {
                    $table->string('product_image')->nullable()->after('product_price');
                } else {
                    $table->string('product_image')->nullable();
                }
            }

            if (!Schema::hasColumn('wholesaler_orders', 'call_status')) {
                if (Schema::hasColumn('wholesaler_orders', 'platform')) {
                    $table->string('call_status')->default('Pending Call')->after('platform');
                } else {
                    $table->string('call_status')->default('Pending Call');
                }
            }

            if (!Schema::hasColumn('wholesaler_orders', 'payment_status')) {
                if (Schema::hasColumn('wholesaler_orders', 'payment_method')) {
                    $table->string('payment_status')->default('pending')->after('payment_method');
                } else {
                    $table->string('payment_status')->default('pending');
                }
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
