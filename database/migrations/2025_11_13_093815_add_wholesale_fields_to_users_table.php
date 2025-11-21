<?php
// database/migrations/2025_11_13_093815_add_wholesale_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWholesaleFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add wholesale fields at the end of the table to avoid column dependency
            $table->enum('seller_type', ['retail', 'wholesale', 'system'])->default('retail')->after('type');
            $table->boolean('is_wholesale_seller')->default(false)->after('seller_type');
            $table->decimal('wholesale_min_order', 10, 2)->nullable();
            $table->decimal('wholesale_discount', 5, 2)->nullable();
            $table->json('wholesale_categories')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'seller_type',
                'is_wholesale_seller',
                'wholesale_min_order',
                'wholesale_discount',
                'wholesale_categories'
            ]);
        });
    }
}
