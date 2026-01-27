<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if table exists before modifying
        if (Schema::hasTable('seller_categories')) {
            // Add missing columns if needed
            Schema::table('seller_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('seller_categories', 'wholesaler_id')) {
                    $table->foreignId('wholesaler_id')->nullable()->constrained('wholesalers')->onDelete('cascade')->after('seller_id');
                }

                if (!Schema::hasColumn('seller_categories', 'seller_code')) {
                    $table->tinyInteger('seller_code')->default(1)->after('cancel');
                }

                // Add other missing columns similarly
            });
        }
    }

    public function down()
    {
        // Optional: Reverse the changes
        Schema::table('seller_categories', function (Blueprint $table) {
            // Remove columns if needed
            // $table->dropColumn(['wholesaler_id', 'seller_code']);
        });
    }
};
