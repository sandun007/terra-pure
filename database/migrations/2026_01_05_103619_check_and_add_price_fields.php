<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get current columns
        $columns = Schema::getColumnListing('wholesaler_products');

        Schema::table('wholesaler_products', function (Blueprint $table) use ($columns) {
            // Only add if column doesn't exist
            if (!in_array('profit_margin_percentage', $columns)) {
                // Find a column that exists to use as 'after'
                if (in_array('discount', $columns)) {
                    $table->decimal('profit_margin_percentage', 5, 2)->default(30)->after('discount');
                } else {
                    $table->decimal('profit_margin_percentage', 5, 2)->default(30);
                }
            }

            if (!in_array('last_price_updated_by', $columns)) {
                $table->unsignedBigInteger('last_price_updated_by')->nullable();
            }

            if (!in_array('last_price_update_at', $columns)) {
                $table->timestamp('last_price_update_at')->nullable();
            }

            if (!in_array('previous_selling_price', $columns)) {
                if (in_array('selling_price', $columns)) {
                    $table->decimal('previous_selling_price', 15, 2)->nullable()->after('selling_price');
                } else {
                    $table->decimal('previous_selling_price', 15, 2)->nullable();
                }
            }
        });

        // Add foreign key constraint if column was added
        if (!in_array('last_price_updated_by', $columns)) {
            Schema::table('wholesaler_products', function (Blueprint $table) {
                $table->foreign('last_price_updated_by')
                    ->references('id')
                    ->on('employees')
                    ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('wholesaler_products', 'last_price_updated_by')) {
                $table->dropForeign(['last_price_updated_by']);
            }

            // Drop columns
            $columnsToDrop = [];

            if (Schema::hasColumn('wholesaler_products', 'profit_margin_percentage')) {
                $columnsToDrop[] = 'profit_margin_percentage';
            }
            if (Schema::hasColumn('wholesaler_products', 'last_price_updated_by')) {
                $columnsToDrop[] = 'last_price_updated_by';
            }
            if (Schema::hasColumn('wholesaler_products', 'last_price_update_at')) {
                $columnsToDrop[] = 'last_price_update_at';
            }
            if (Schema::hasColumn('wholesaler_products', 'previous_selling_price')) {
                $columnsToDrop[] = 'previous_selling_price';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
