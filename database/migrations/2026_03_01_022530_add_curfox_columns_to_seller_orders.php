<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Curfox API city/state columns to seller_orders so each order
 * can carry the validated delivery destination for courier dispatch.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            // Store the Curfox city ID (from /api/public/merchant/state endpoint)
            $table->unsignedInteger('destination_city_id')
                ->nullable()
                ->after('district')
                ->comment('Curfox city ID from /state API');

            $table->string('destination_city_name', 150)
                ->nullable()
                ->after('destination_city_id');

            $table->string('destination_state_name', 150)
                ->nullable()
                ->after('destination_city_name');

            // Track whether this order has been dispatched to Curfox
            $table->string('curfox_waybill', 50)
                ->nullable()
                ->after('destination_state_name')
                ->comment('Waybill returned by Curfox after dispatch');

            $table->timestamp('curfox_dispatched_at')
                ->nullable()
                ->after('curfox_waybill');

            // Index for fast lookup of un-dispatched orders
            $table->index('curfox_waybill');
            $table->index('destination_city_id');
        });
    }

    public function down(): void
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            $table->dropIndex(['curfox_waybill']);
            $table->dropIndex(['destination_city_id']);
            $table->dropColumn([
                'destination_city_id',
                'destination_city_name',
                'destination_state_name',
                'curfox_waybill',
                'curfox_dispatched_at',
            ]);
        });
    }
};
