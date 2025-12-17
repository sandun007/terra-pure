<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Safe fix for order_no to order_number
     */
    public function up()
    {
        // Check if orders table exists
        if (!Schema::hasTable('orders')) {
            return;
        }

        // Check if order_no column exists
        $hasOrderNo = Schema::hasColumn('orders', 'order_no');
        $hasOrderNumber = Schema::hasColumn('orders', 'order_number');

        if ($hasOrderNo && !$hasOrderNumber) {
            // Case 1: Only order_no exists, rename it
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('order_no', 'order_number');
            });
        } elseif ($hasOrderNo && $hasOrderNumber) {
            // Case 2: Both exist, copy data and drop order_no
            DB::statement('UPDATE orders SET order_number = order_no WHERE order_number IS NULL OR order_number = ""');

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_no');
            });
        }

        // Ensure order_number is properly indexed
        if ($hasOrderNumber || $hasOrderNo) {
            try {
                Schema::table('orders', function (Blueprint $table) {
                    // Drop existing index if it exists
                    $table->dropUnique(['order_number']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, that's fine
            }

            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->unique('order_number');
                });
            } catch (\Exception $e) {
                // Index might already exist
            }
        }

        // Ensure all required columns exist
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('order_number');
            }
            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('orders', 'shipping')) {
                $table->decimal('shipping', 10, 2)->default(0)->after('tax');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'order_date')) {
                $table->timestamp('order_date')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('order_date');
            }
            if (!Schema::hasColumn('orders', 'status_notes')) {
                $table->text('status_notes')->nullable()->after('status_updated_at');
            }
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status_notes');
            }
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        // Don't reverse this migration to avoid data loss
        // If you need to rollback, do it manually
    }
};
