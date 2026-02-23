<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add deleted_at if it doesn't exist
        if (!Schema::hasColumn('wholesaler_orders', 'deleted_at')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('wholesaler_orders', 'deleted_at')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
