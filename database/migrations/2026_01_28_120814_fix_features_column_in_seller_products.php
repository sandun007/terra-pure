<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Change features column from string to text
        if (Schema::hasTable('seller_products') && Schema::hasColumn('seller_products', 'features')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->text('features')->nullable()->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('seller_products') && Schema::hasColumn('seller_products', 'features')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->string('features', 255)->nullable()->change();
            });
        }
    }
};
