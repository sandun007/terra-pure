<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seller_calls', function (Blueprint $table) {
            $table->unique('order_code');
        });

        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->unique('order_code');
        });
    }

    public function down(): void
    {
        Schema::table('seller_calls', function (Blueprint $table) {
            $table->dropUnique(['order_code']);
        });

        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->dropUnique(['order_code']);
        });
    }
};
