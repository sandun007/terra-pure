<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalSoldToWholesalerProductsTable extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_products', 'total_sold')) {
                $table->integer('total_sold')->default(0)->after('qty');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $table->dropColumn('total_sold');
        });
    }
}
