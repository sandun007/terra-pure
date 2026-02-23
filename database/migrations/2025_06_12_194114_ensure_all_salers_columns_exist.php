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
            // Basic Information
            if (!Schema::hasColumn('salers', 'name')) {
                $table->string('name')->after('code');
            }

            if (!Schema::hasColumn('salers', 'email')) {
                $table->string('email')->unique()->after('name');
            }

            // Contact Information
            if (!Schema::hasColumn('salers', 'phone')) {
                $table->string('phone')->after('view_password');
            }

            if (!Schema::hasColumn('salers', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }

            // Location Information
            if (!Schema::hasColumn('salers', 'address')) {
                $table->text('address')->after('whatsapp');
            }

            if (!Schema::hasColumn('salers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (!Schema::hasColumn('salers', 'district')) {
                $table->string('district')->nullable()->after('city');
            }

            if (!Schema::hasColumn('salers', 'state')) {
                $table->string('state')->nullable()->after('district');
            }

            if (!Schema::hasColumn('salers', 'country')) {
                $table->string('country')->default('Sri Lanka')->after('state');
            }

            if (!Schema::hasColumn('salers', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('country');
            }

            // NIC/Identity
            if (!Schema::hasColumn('salers', 'NIC_no')) {
                $table->string('NIC_no')->nullable()->after('postal_code');
            }

            if (!Schema::hasColumn('salers', 'NIC_front')) {
                $table->string('NIC_front')->nullable()->after('NIC_no');
            }

            if (!Schema::hasColumn('salers', 'NIC_back')) {
                $table->string('NIC_back')->nullable()->after('NIC_front');
            }

            // Profile Images
            if (!Schema::hasColumn('salers', 'img')) {
                $table->string('img')->nullable()->after('NIC_back');
            }

            if (!Schema::hasColumn('salers', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('img');
            }

            // Business Information
            if (!Schema::hasColumn('salers', 'type')) {
                $table->string('type')->nullable()->after('profile_image');
            }

            if (!Schema::hasColumn('salers', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])
                    ->default('pending')
                    ->after('type');
            }

            // Financial Information
            if (!Schema::hasColumn('salers', 'deposit')) {
                $table->decimal('deposit', 15, 2)->default(0)->after('status');
            }

            if (!Schema::hasColumn('salers', 'cal_deposit')) {
                $table->decimal('cal_deposit', 15, 2)->default(0)->after('deposit');
            }

            if (!Schema::hasColumn('salers', 'cost')) {
                $table->decimal('cost', 15, 2)->default(0)->after('cal_deposit');
            }

            if (!Schema::hasColumn('salers', 'total_sales')) {
                $table->decimal('total_sales', 15, 2)->default(0)->after('cost');
            }

            // Statistics
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
            $columns = [
                'postal_code', 'NIC_no', 'NIC_front', 'NIC_back',
                'img', 'profile_image', 'type', 'status',
                'deposit', 'cal_deposit', 'cost', 'total_sales', 'order_count'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('salers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
