<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->unsignedBigInteger('call_id')->nullable()->after('product_id');
            $table->foreign('call_id')->references('id')->on('seller_calls')->onDelete('set null');
            $table->index('call_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->dropForeign(['call_id']);
            $table->dropIndex(['call_id']);
            $table->dropColumn('call_id');
        });
    }
};
