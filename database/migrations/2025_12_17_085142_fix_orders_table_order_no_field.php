<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Make order_no nullable temporarily
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_no')->nullable()->change();
        });

        // Copy order_number to order_no for existing records
        DB::statement('UPDATE orders SET order_no = order_number WHERE order_no IS NULL OR order_no = ""');

        // Make order_no unique
        Schema::table('orders', function (Blueprint $table) {
            $table->unique('order_no');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['order_no']);
        });
    }
};
