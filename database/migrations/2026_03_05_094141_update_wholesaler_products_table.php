<?php
// database/migrations/2026_03_05_094005_update_wholesaler_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWholesalerProductsTable extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columns = Schema::getColumnListing('wholesaler_products');

            // Add stock if it doesn't exist
            if (!in_array('stock', $columns)) {
                $table->integer('stock')->default(0)->after('id');
            }

            // Add price if it doesn't exist (common column for products)
            if (!in_array('price', $columns)) {
                $table->decimal('price', 10, 2)->default(0)->after('stock');
            }

            // Add other common columns if needed
            if (!in_array('quantity', $columns)) {
                $table->integer('quantity')->default(0)->after('price');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columns = ['stock', 'price', 'quantity'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('wholesaler_products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
