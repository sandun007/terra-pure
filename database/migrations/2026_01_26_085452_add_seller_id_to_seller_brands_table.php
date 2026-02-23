<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('seller_products', 'seller_brand_id') &&
            !Schema::hasColumn('seller_products', 'brand_id')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->renameColumn('seller_brand_id', 'brand_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('seller_products', 'brand_id')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->renameColumn('brand_id', 'seller_brand_id');
            });
        }
    }
};
