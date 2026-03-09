<?php
// database/migrations/2026_03_05_094005_add_stock_to_wholesaler_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockToWholesalerProductsTable extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // First check if price column exists before using after()
            if (!Schema::hasColumn('wholesaler_products', 'stock')) {
                // Add stock column at the end if price doesn't exist
                $table->integer('stock')->default(0)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (Schema::hasColumn('wholesaler_products', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
}
