<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('salers', function (Blueprint $table) {
            // Add columns in order, referencing only existing columns

            if (!Schema::hasColumn('salers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (!Schema::hasColumn('salers', 'state')) {
                $table->string('state')->nullable()->after('district');
            }

            if (!Schema::hasColumn('salers', 'country')) {
                $table->string('country')->default('Sri Lanka')->after('state');
            }

            if (!Schema::hasColumn('salers', 'img')) {
                $table->string('img')->nullable()->after('NIC_back');
            }

            if (!Schema::hasColumn('salers', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('img');
            }
        });

        // Add columns that reference 'profile_image' in a separate schema call
        // This ensures 'profile_image' exists before we reference it
        Schema::table('salers', function (Blueprint $table) {
            if (!Schema::hasColumn('salers', 'type')) {
                $table->string('type')->nullable()->after('profile_image');
            }

            if (!Schema::hasColumn('salers', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])
                    ->default('pending')
                    ->after('type');
            }

            if (!Schema::hasColumn('salers', 'total_sales')) {
                $table->decimal('total_sales', 15, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('salers', 'order_count')) {
                $table->integer('order_count')->default(0)->after('total_sales');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('salers', function (Blueprint $table) {
            $columns = ['type', 'city', 'state', 'country', 'img', 'profile_image', 'status', 'total_sales', 'order_count'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('salers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
