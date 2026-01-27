<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seller_products', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_products', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('id');
                $table->index('seller_id');
            }
        });
    }

    public function down()
    {
        Schema::table('seller_products', function (Blueprint $table) {
            if (Schema::hasColumn('seller_products', 'seller_id')) {
                $table->dropColumn('seller_id');
            }
        });
    }
};
