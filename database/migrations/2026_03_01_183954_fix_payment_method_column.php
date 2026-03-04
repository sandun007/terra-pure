<?php
// database/migrations/2026_03_02_000002_fix_payment_method_column.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the table exists
        if (Schema::hasTable('wholesaler_orders')) {

            // Option 1: If it's an ENUM column, change it to VARCHAR
            $columnType = DB::select("SHOW COLUMNS FROM wholesaler_orders WHERE Field = 'payment_method'");

            if (!empty($columnType)) {
                $type = $columnType[0]->Type;

                // If it's an enum, convert to varchar
                if (strpos($type, 'enum') === 0) {
                    Schema::table('wholesaler_orders', function (Blueprint $table) {
                        $table->string('payment_method', 50)->nullable()->default('cash')->change();
                    });
                }
                // If it's varchar, increase the length
                elseif (strpos($type, 'varchar') === 0) {
                    Schema::table('wholesaler_orders', function (Blueprint $table) {
                        $table->string('payment_method', 50)->nullable()->default('cash')->change();
                    });
                }
            } else {
                // Column doesn't exist, add it
                Schema::table('wholesaler_orders', function (Blueprint $table) {
                    $table->string('payment_method', 50)->nullable()->default('cash')->after('total_amount');
                });
            }
        }
    }

    public function down(): void
    {
        // Revert back if needed
        if (Schema::hasTable('wholesaler_orders') && Schema::hasColumn('wholesaler_orders', 'payment_method')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->string('payment_method', 20)->nullable()->default('cash')->change();
            });
        }
    }
};
