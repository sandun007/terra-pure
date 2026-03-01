<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('call_id')->nullable()->after('seller_id');

            $table->foreign('call_id')
                ->references('id')
                ->on('seller_calls')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            $table->dropForeign(['call_id']);
            $table->dropColumn('call_id');
        });
    }
};
