<?php
// filename: database/migrations/2026_01_28_140000_rename_img_to_image_in_seller_products.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename img column to image if it exists
        if (Schema::hasTable('seller_products') && Schema::hasColumn('seller_products', 'img')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->renameColumn('img', 'image');
            });
        }

        // Also make sure the column exists with correct type
        if (Schema::hasTable('seller_products') && !Schema::hasColumn('seller_products', 'image')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->string('image')->nullable()->after('barcode');
            });
        }
    }

    public function down()
    {
        // Revert the change
        if (Schema::hasTable('seller_products') && Schema::hasColumn('seller_products', 'image')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->renameColumn('image', 'img');
            });
        }
    }
};
