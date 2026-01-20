<?php

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
        Schema::table('employees', function (Blueprint $table) {
            // Add phone column if it doesn't exist
            if (!Schema::hasColumn('employees', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }

            // Add img column if it doesn't exist
            if (!Schema::hasColumn('employees', 'img')) {
                $table->string('img')->nullable()->after('password');
            }

            // Add other potentially missing columns
            if (!Schema::hasColumn('employees', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('employees', 'district')) {
                $table->string('district', 100)->nullable()->after('address');
            }

            if (!Schema::hasColumn('employees', 'post_code')) {
                $table->string('post_code', 20)->nullable()->after('district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $columns = ['phone', 'img', 'address', 'district', 'post_code'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
