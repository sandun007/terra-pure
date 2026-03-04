<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            // Origin city — overrideable per delivery call, falls back to system default
            $table->unsignedInteger('origin_city_id')->nullable()->after('destination_state_name');
            $table->string('origin_city_name', 150)->nullable()->after('origin_city_id');
            $table->string('origin_state_name', 150)->nullable()->after('origin_city_name');
        });
    }

    public function down(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->dropColumn(['origin_city_id', 'origin_city_name', 'origin_state_name']);
        });
    }
};
