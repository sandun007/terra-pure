<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundFieldsToWholesalerOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('wholesaler_orders', 'refund_status')) {
                $table->enum('refund_status', ['pending', 'refunded', 'failed'])->default('pending')->after('payment_status');
            }
            if (!Schema::hasColumn('wholesaler_orders', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_status');
            }
            if (!Schema::hasColumn('wholesaler_orders', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('refund_amount');
            }
            if (!Schema::hasColumn('wholesaler_orders', 'refunded_by')) {
                $table->unsignedBigInteger('refunded_by')->nullable()->after('refunded_at');
            }
            if (!Schema::hasColumn('wholesaler_orders', 'cancelled_by')) {
                $table->string('cancelled_by')->nullable()->after('refunded_by')->comment('customer, admin, employee, system');
            }
            if (!Schema::hasColumn('wholesaler_orders', 'refund_notes')) {
                $table->text('refund_notes')->nullable()->after('cancelled_by');
            }
        });
    }

    public function down()
    {
        Schema::table('wholesaler_orders', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refund_amount',
                'refunded_at',
                'refunded_by',
                'cancelled_by',
                'refund_notes'
            ]);
        });
    }
}
