<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('wholesaler_orders', 'product_id')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable()->after('wholesaler_id');
                $table->foreign('product_id')->references('id')->on('wholesaler_products')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('wholesaler_orders', 'product_id')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            });
        }
    }
};
