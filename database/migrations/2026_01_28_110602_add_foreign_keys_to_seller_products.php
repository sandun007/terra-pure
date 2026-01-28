<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('seller_products', function (Blueprint $table) {
            // Check if sellers table exists before adding foreign key
            if (Schema::hasTable('sellers') && Schema::hasColumn('sellers', 'id')) {
                $table->foreign('seller_id')
                    ->references('id')
                    ->on('sellers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }

            // Category foreign key if table exists
            if (Schema::hasTable('seller_categories')) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('seller_categories')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }

            // Brand foreign key if table exists
            if (Schema::hasTable('seller_brands')) {
                $table->foreign('brand_id')
                    ->references('id')
                    ->on('seller_brands')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        Schema::table('seller_products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['brand_id']);
        });
    }
};
