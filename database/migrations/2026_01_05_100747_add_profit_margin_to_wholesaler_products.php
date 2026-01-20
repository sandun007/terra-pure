<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_products', 'profit_margin')) {
                $table->decimal('profit_margin', 5, 2)->default(20.00)->after('discount');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $table->dropColumn('profit_margin');
        });
    }
};
