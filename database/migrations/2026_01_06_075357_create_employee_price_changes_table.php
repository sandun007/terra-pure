<?php
// database/migrations/2026_02_01_000002_update_employee_price_changes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employee_price_changes', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_price_changes', 'product_type')) {
                $table->string('product_type')->default('seller')->after('product_id');
                $table->decimal('old_display_price', 10, 2)->nullable()->after('product_type');
                $table->decimal('new_display_price', 10, 2)->nullable()->after('old_display_price');
                $table->decimal('old_stock_price', 10, 2)->nullable()->after('new_display_price');
                $table->decimal('new_stock_price', 10, 2)->nullable()->after('old_stock_price');
                $table->decimal('old_discount', 5, 2)->nullable()->after('new_stock_price');
                $table->decimal('new_discount', 5, 2)->nullable()->after('old_discount');
                $table->string('status')->default('pending')->after('change_reason');
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->timestamp('approved_at')->nullable()->after('approved_by');
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
        });
    }

    public function down()
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
