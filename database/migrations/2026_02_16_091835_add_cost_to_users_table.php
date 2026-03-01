<?php
// database/migrations/xxxx_add_cost_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // First check if deposit column exists, if not add it
            if (!Schema::hasColumn('users', 'deposit')) {
                $table->decimal('deposit', 10, 2)->default(0)->after('id');
            }

            // Add cost column
            if (!Schema::hasColumn('users', 'cost')) {
                $table->decimal('cost', 10, 2)->default(0)->after('deposit');
            }

            // Add cal_deposit column
            if (!Schema::hasColumn('users', 'cal_deposit')) {
                $table->decimal('cal_deposit', 10, 2)->nullable()->after('cost');
            }

            // Add last_row_count column
            if (!Schema::hasColumn('users', 'last_row_count')) {
                $table->integer('last_row_count')->default(0)->after('cal_deposit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['deposit', 'cost', 'cal_deposit', 'last_row_count'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
