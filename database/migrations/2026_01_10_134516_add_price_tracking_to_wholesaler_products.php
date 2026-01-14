<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_products', 'last_price_updated_by')) {
                $table->unsignedBigInteger('last_price_updated_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('wholesaler_products', 'last_price_updated_at')) {
                $table->timestamp('last_price_updated_at')->nullable()->after('last_price_updated_by');
            }
            if (!Schema::hasColumn('wholesaler_products', 'price_changes_count')) {
                $table->integer('price_changes_count')->default(0)->after('last_price_updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $columns = ['last_price_updated_by', 'last_price_updated_at', 'price_changes_count'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('wholesaler_products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
