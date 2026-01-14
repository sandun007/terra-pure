<?php
// database/migrations/2025_12_15_144952_update_wholesaler_products_table_fixed.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Add missing fields for employee price management
            if (!Schema::hasColumn('wholesaler_products', 'display_price')) {
                $table->decimal('display_price', 15, 2)->nullable()->after('selling_price');
            }

            if (!Schema::hasColumn('wholesaler_products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 15, 2)->nullable()->after('display_price');
            }

            // Add computed price fields if they don't exist
            if (!Schema::hasColumn('wholesaler_products', 'final_price')) {
                $table->decimal('final_price', 15, 2)->nullable()->virtualAs(
                    DB::raw('CASE WHEN discount > 0 THEN selling_price - (selling_price * discount / 100) ELSE selling_price END')
                );
            }

            if (!Schema::hasColumn('wholesaler_products', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->nullable()->virtualAs(
                    DB::raw('cost_price + packaging_cost')
                );
            }

            if (!Schema::hasColumn('wholesaler_products', 'profit_amount')) {
                $table->decimal('profit_amount', 15, 2)->nullable()->virtualAs(
                    DB::raw('CASE WHEN discount > 0 THEN (selling_price - (selling_price * discount / 100)) - (cost_price + packaging_cost) ELSE selling_price - (cost_price + packaging_cost) END')
                );
            }

            if (!Schema::hasColumn('wholesaler_products', 'profit_margin')) {
                $table->decimal('profit_margin', 5, 2)->nullable()->virtualAs(
                    DB::raw('CASE WHEN (cost_price + packaging_cost) > 0 THEN
                        (CASE WHEN discount > 0
                            THEN ((selling_price - (selling_price * discount / 100)) - (cost_price + packaging_cost)) / (cost_price + packaging_cost) * 100
                            ELSE (selling_price - (cost_price + packaging_cost)) / (cost_price + packaging_cost) * 100
                        END)
                        ELSE 0 END')
                );
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('wholesaler_products', 'display_price')) {
                $columnsToDrop[] = 'display_price';
            }
            if (Schema::hasColumn('wholesaler_products', 'wholesale_price')) {
                $columnsToDrop[] = 'wholesale_price';
            }
            if (Schema::hasColumn('wholesaler_products', 'final_price')) {
                $columnsToDrop[] = 'final_price';
            }
            if (Schema::hasColumn('wholesaler_products', 'total_cost')) {
                $columnsToDrop[] = 'total_cost';
            }
            if (Schema::hasColumn('wholesaler_products', 'profit_amount')) {
                $columnsToDrop[] = 'profit_amount';
            }
            if (Schema::hasColumn('wholesaler_products', 'profit_margin')) {
                $columnsToDrop[] = 'profit_margin';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
