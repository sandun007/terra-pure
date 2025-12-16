<?php
// database/migrations/2025_12_15_144952_update_wholesaler_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Add new fields only if they don't exist
            if (!Schema::hasColumn('wholesaler_products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->default(0)->after('description');
            }

            if (!Schema::hasColumn('wholesaler_products', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->default(0)->after('cost_price');
            }

            if (!Schema::hasColumn('wholesaler_products', 'discount')) {
                $table->decimal('discount', 5, 2)->default(0)->after('selling_price');
            }

            if (!Schema::hasColumn('wholesaler_products', 'packaging_cost')) {
                $table->decimal('packaging_cost', 10, 2)->default(0)->after('discount');
            }

            if (!Schema::hasColumn('wholesaler_products', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('packaging_cost');
            }

            if (!Schema::hasColumn('wholesaler_products', 'min_stock_level')) {
                $table->integer('min_stock_level')->default(10)->after('stock_quantity');
            }
        });

        // Handle renames in a separate schema call to avoid conflicts
        if (Schema::hasColumn('wholesaler_products', 'qty') &&
            !Schema::hasColumn('wholesaler_products', 'stock_quantity')) {
            Schema::table('wholesaler_products', function (Blueprint $table) {
                $table->renameColumn('qty', 'stock_quantity');
            });
        }

        if (Schema::hasColumn('wholesaler_products', 'min_order_quantity') &&
            !Schema::hasColumn('wholesaler_products', 'min_stock_level')) {
            Schema::table('wholesaler_products', function (Blueprint $table) {
                $table->renameColumn('min_order_quantity', 'min_stock_level');
            });
        }

        // Drop old columns in a separate schema call
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('wholesaler_products', 'display_price')) {
                $columnsToDrop[] = 'display_price';
            }

            if (Schema::hasColumn('wholesaler_products', 'wholesale_price')) {
                $columnsToDrop[] = 'wholesale_price';
            }

            if (Schema::hasColumn('wholesaler_products', 'weight')) {
                $columnsToDrop[] = 'weight';
            }

            if (Schema::hasColumn('wholesaler_products', 'dimensions')) {
                $columnsToDrop[] = 'dimensions';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Drop added columns if they exist
            $columnsToDrop = [];

            if (Schema::hasColumn('wholesaler_products', 'cost_price')) {
                $columnsToDrop[] = 'cost_price';
            }
            if (Schema::hasColumn('wholesaler_products', 'selling_price')) {
                $columnsToDrop[] = 'selling_price';
            }
            if (Schema::hasColumn('wholesaler_products', 'discount')) {
                $columnsToDrop[] = 'discount';
            }
            if (Schema::hasColumn('wholesaler_products', 'packaging_cost')) {
                $columnsToDrop[] = 'packaging_cost';
            }
            if (Schema::hasColumn('wholesaler_products', 'stock_quantity')) {
                $columnsToDrop[] = 'stock_quantity';
            }
            if (Schema::hasColumn('wholesaler_products', 'min_stock_level')) {
                $columnsToDrop[] = 'min_stock_level';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
