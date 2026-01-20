<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First check if table exists
        if (!Schema::hasTable('employee_price_changes')) {
            Schema::create('employee_price_changes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

                // Cost prices (nullable)
                $table->decimal('old_cost_price', 15, 2)->nullable();
                $table->decimal('new_cost_price', 15, 2)->nullable();

                // Selling prices (employee view)
                $table->decimal('old_selling_price', 15, 2);
                $table->decimal('new_selling_price', 15, 2);

                // Display prices (wholesaler view)
                $table->decimal('old_display_price', 15, 2);
                $table->decimal('new_display_price', 15, 2);

                // Final prices (after discount)
                $table->decimal('old_final_price', 15, 2);
                $table->decimal('new_final_price', 15, 2);

                // Change calculations
                $table->decimal('price_difference', 15, 2);
                $table->decimal('percentage_change', 8, 2);

                // Metadata
                $table->text('notes')->nullable();
                $table->string('change_reason')->default('manual_update');

                $table->timestamps();

                // Indexes
                $table->index('product_id');
                $table->index('employee_id');
                $table->index('created_at');
            });
        } else {
            // If table exists, modify it to add display price columns
            Schema::table('employee_price_changes', function (Blueprint $table) {
                // Add display price columns if they don't exist
                if (!Schema::hasColumn('employee_price_changes', 'old_display_price')) {
                    $table->decimal('old_display_price', 15, 2)->after('new_selling_price');
                }
                if (!Schema::hasColumn('employee_price_changes', 'new_display_price')) {
                    $table->decimal('new_display_price', 15, 2)->after('old_display_price');
                }

                // Ensure other required columns exist
                if (!Schema::hasColumn('employee_price_changes', 'old_final_price')) {
                    $table->decimal('old_final_price', 15, 2)->after('new_display_price');
                }
                if (!Schema::hasColumn('employee_price_changes', 'new_final_price')) {
                    $table->decimal('new_final_price', 15, 2)->after('old_final_price');
                }
                if (!Schema::hasColumn('employee_price_changes', 'price_difference')) {
                    $table->decimal('price_difference', 15, 2)->after('new_final_price');
                }
                if (!Schema::hasColumn('employee_price_changes', 'percentage_change')) {
                    $table->decimal('percentage_change', 8, 2)->after('price_difference');
                }
                if (!Schema::hasColumn('employee_price_changes', 'change_reason')) {
                    $table->string('change_reason')->default('manual_update')->after('notes');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('employee_price_changes', function (Blueprint $table) {
            $columns = [
                'old_display_price',
                'new_display_price',
                'old_final_price',
                'new_final_price',
                'price_difference',
                'percentage_change',
                'change_reason'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('employee_price_changes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
