<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerOrdersTable extends Migration
{
    /**
     * Run the migrations - COMPLETE FIX
     */
    public function up()
    {
        // Check if orders table exists
        if (!Schema::hasTable('orders')) {
            // Create fresh orders table if it doesn't exist
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
                $table->string('order_number')->unique();
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('shipping', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->string('payment_method');
                $table->string('payment_status')->default('pending');
                $table->string('status')->default('pending');
                $table->text('shipping_address');
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('customer_phone');
                $table->text('notes')->nullable();
                $table->datetime('order_date');
                $table->datetime('status_updated_at')->nullable();
                $table->text('status_notes')->nullable();
                $table->datetime('cancelled_at')->nullable();
                $table->timestamps();

                $table->index('order_number');
                $table->index('status');
                $table->index('payment_status');
                $table->index(['wholesaler_id', 'status']);
            });
        } else {
            // Table exists, update it
            Schema::table('orders', function (Blueprint $table) {
                // Check if order_number column exists
                if (!Schema::hasColumn('orders', 'order_number')) {
                    $table->string('order_number')->nullable()->after('id');
                }

                // Add other missing columns
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
            });

            // Migrate data from order_no to order_number if order_no exists
            if (Schema::hasColumn('orders', 'order_no')) {
                DB::statement('UPDATE orders SET order_number = order_no WHERE order_number IS NULL');

                // After migration, you can optionally drop order_no
                // Schema::table('orders', function (Blueprint $table) {
                //     $table->dropColumn('order_no');
                // });
            }

            // Make order_number unique and not nullable
            DB::statement('ALTER TABLE orders MODIFY order_number VARCHAR(255) NOT NULL');

            // Add unique index if it doesn't exist
            $indexes = DB::select("SHOW INDEX FROM orders WHERE Key_name = 'orders_order_number_unique'");
            if (empty($indexes)) {
                DB::statement('ALTER TABLE orders ADD UNIQUE KEY orders_order_number_unique (order_number)');
            }
        }

        // Create wholesaler_order_items table if it doesn't exist
        if (!Schema::hasTable('wholesaler_order_items')) {
            Schema::create('wholesaler_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
                $table->string('product_name');
                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->timestamps();

                $table->index('order_id');
                $table->index('product_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('wholesaler_order_items');
        // Don't drop orders table to preserve data
        // Schema::dropIfExists('orders');
    }
};
