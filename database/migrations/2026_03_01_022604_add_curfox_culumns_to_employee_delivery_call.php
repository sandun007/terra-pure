<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Curfox dispatch tracking columns to employee_delivery_call.
 * When status reaches 'givedriver' the system calls Curfox /order/bulk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->unsignedInteger('destination_city_id')
                ->nullable()
                ->after('district')
                ->comment('Curfox city ID');

            $table->string('destination_state_name', 150)
                ->nullable()
                ->after('destination_city_id');

            $table->string('curfox_waybill', 50)
                ->nullable()
                ->after('destination_state_name')
                ->comment('Waybill returned by Curfox');

            $table->timestamp('curfox_dispatched_at')
                ->nullable()
                ->after('curfox_waybill');

            $table->text('curfox_error')
                ->nullable()
                ->after('curfox_dispatched_at')
                ->comment('Last Curfox API error for this record');

            $table->index('curfox_waybill');
            $table->index('destination_city_id');
        });
    }

    public function down(): void
    {
        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->dropIndex(['curfox_waybill']);
            $table->dropIndex(['destination_city_id']);
            $table->dropColumn([
                'destination_city_id',
                'destination_state_name',
                'curfox_waybill',
                'curfox_dispatched_at',
                'curfox_error',
            ]);
        });
    }
};
