<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_products', 'display_price')) {
                $table->decimal('display_price', 15, 2)->nullable()->after('selling_price');
            }
        });

        // Set display_price to selling_price for existing records
        DB::table('wholesaler_products')
            ->whereNull('display_price')
            ->orWhere('display_price', 0)
            ->update(['display_price' => DB::raw('selling_price')]);
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (Schema::hasColumn('wholesaler_products', 'display_price')) {
                $table->dropColumn('display_price');
            }
        });
    }
};
