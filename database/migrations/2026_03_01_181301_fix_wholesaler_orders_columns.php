<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            // Check and add product_code if missing
            if (!Schema::hasColumn('wholesaler_orders', 'product_code')) {
                $table->string('product_code')->nullable()->after('wholesaler_id');
            }

            // Check and add product_id if missing
            if (!Schema::hasColumn('wholesaler_orders', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('product_code');
                $table->foreign('product_id')->references('id')->on('wholesaler_products')->onDelete('set null');
            }

            // Check and add product_image if missing - FIXED: removed 'after' clause
            if (!Schema::hasColumn('wholesaler_orders', 'product_image')) {
                $table->string('product_image')->nullable();
            }

            // Check and add call_status if missing - FIXED: removed 'after' clause
            if (!Schema::hasColumn('wholesaler_orders', 'call_status')) {
                $table->string('call_status')->default('Pending Call');
            }

            // Check and add payment_status if missing - FIXED: removed 'after' clause
            if (!Schema::hasColumn('wholesaler_orders', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            $columns = ['product_code', 'product_image', 'call_status', 'payment_status'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('wholesaler_orders', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('wholesaler_orders', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
        });
    }
};
