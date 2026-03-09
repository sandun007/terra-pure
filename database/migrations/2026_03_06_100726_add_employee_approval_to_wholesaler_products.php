<?php
// database/migrations/2026_03_06_000001_add_employee_approval_to_wholesaler_products.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_products', 'employee_approval_status')) {
                $table->enum('employee_approval_status', ['pending', 'approved', 'rejected'])
                    ->default('pending')
                    ->after('status');
            }

            if (!Schema::hasColumn('wholesaler_products', 'approved_by_employee')) {
                $table->foreignId('approved_by_employee')
                    ->nullable()
                    ->constrained('employees')
                    ->onDelete('set null')
                    ->after('employee_approval_status');
            }

            if (!Schema::hasColumn('wholesaler_products', 'approved_at')) {
                $table->timestamp('approved_at')
                    ->nullable()
                    ->after('approved_by_employee');
            }

            if (!Schema::hasColumn('wholesaler_products', 'rejection_reason')) {
                $table->text('rejection_reason')
                    ->nullable()
                    ->after('approved_at');
            }

            if (!Schema::hasColumn('wholesaler_products', 'display_in_seller_shop')) {
                $table->boolean('display_in_seller_shop')
                    ->default(false)
                    ->after('rejection_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columns = [
                'employee_approval_status',
                'approved_by_employee',
                'approved_at',
                'rejection_reason',
                'display_in_seller_shop'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('wholesaler_products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
