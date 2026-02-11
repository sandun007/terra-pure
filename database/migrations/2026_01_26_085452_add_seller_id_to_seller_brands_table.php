<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if column doesn't exist before adding
        if (!Schema::hasColumn('seller_brands', 'seller_id')) {
            Schema::table('seller_brands', function (Blueprint $table) {
                // First, remove any existing foreign key constraints
                $table->dropForeign(['seller_id']);
            });

            Schema::table('seller_brands', function (Blueprint $table) {
                // Add seller_id column if it doesn't exist
                $table->foreignId('seller_id')->after('id')->constrained('sellers')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::table('seller_brands', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
};
