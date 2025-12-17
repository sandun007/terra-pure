<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('orders', 'order_no')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_no');
            });
        }
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_no')->nullable()->after('id');
        });
    }
};
