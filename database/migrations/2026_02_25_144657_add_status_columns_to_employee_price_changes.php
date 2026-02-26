<?php
// database/migrations/2026_02_02_000001_add_status_columns_to_employee_price_changes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_price_changes', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('employee_price_changes', 'product_type')) {
                $table->string('product_type')->default('seller')->after('product_id');
            }

            if (!Schema::hasColumn('employee_price_changes', 'old_display_price')) {
                $table->decimal('old_display_price', 10, 2)->nullable()->after('product_type');
            }

            if (!Schema::hasColumn('employee_price_changes', 'new_display_price')) {
                $table->decimal('new_display_price', 10, 2)->nullable()->after('old_display_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'old_stock_price')) {
                $table->decimal('old_stock_price', 10, 2)->nullable()->after('new_display_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'new_stock_price')) {
                $table->decimal('new_stock_price', 10, 2)->nullable()->after('old_stock_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'old_discount')) {
                $table->decimal('old_discount', 5, 2)->nullable()->after('new_stock_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'new_discount')) {
                $table->decimal('new_discount', 5, 2)->nullable()->after('old_discount');
            }

            if (!Schema::hasColumn('employee_price_changes', 'old_packaging_cost')) {
                $table->decimal('old_packaging_cost', 10, 2)->nullable()->after('new_discount');
            }

            if (!Schema::hasColumn('employee_price_changes', 'new_packaging_cost')) {
                $table->decimal('new_packaging_cost', 10, 2)->nullable()->after('old_packaging_cost');
            }

            if (!Schema::hasColumn('employee_price_changes', 'old_final_price')) {
                $table->decimal('old_final_price', 10, 2)->nullable()->after('new_packaging_cost');
            }

            if (!Schema::hasColumn('employee_price_changes', 'new_final_price')) {
                $table->decimal('new_final_price', 10, 2)->nullable()->after('old_final_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'price_difference')) {
                $table->decimal('price_difference', 10, 2)->nullable()->after('new_final_price');
            }

            if (!Schema::hasColumn('employee_price_changes', 'percentage_change')) {
                $table->decimal('percentage_change', 10, 2)->nullable()->after('price_difference');
            }

            // Add status column (this is the one causing the error)
            if (!Schema::hasColumn('employee_price_changes', 'status')) {
                $table->string('status')->default('pending')->after('change_reason');
            }

            if (!Schema::hasColumn('employee_price_changes', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            }

            if (!Schema::hasColumn('employee_price_changes', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            if (!Schema::hasColumn('employee_price_changes', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
            }

            if (!Schema::hasColumn('employee_price_changes', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }

            if (!Schema::hasColumn('employee_price_changes', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_price_changes', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'old_display_price',
                'new_display_price',
                'old_stock_price',
                'new_stock_price',
                'old_discount',
                'new_discount',
                'old_packaging_cost',
                'new_packaging_cost',
                'old_final_price',
                'new_final_price',
                'price_difference',
                'percentage_change',
                'status',
                'approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at',
                'rejection_reason'
            ]);
        });
    }
};
