<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wholesaler_order_items');
        Schema::dropIfExists('wholesaler_orders');

        Schema::create('wholesaler_orders', function (Blueprint $table) {
            $table->id();

            // Order identifier
            $table->string('order_number')->unique();

            // Wholesaler reference
            $table->unsignedBigInteger('wholesaler_id');

            // Product information
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->string('product_image')->nullable();
            $table->integer('quantity')->default(1);

            // Customer information
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('district')->nullable();

            // Order details
            $table->string('platform')->nullable()->default('manual');
            $table->string('call_status')->default('Pending Call');
            $table->text('remark')->nullable();

            // Dates
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->date('rescheduling_date')->nullable();
            $table->date('return_date')->nullable();

            // Invoice fields
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();

            // Financial
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable()->default('cash');
            $table->string('payment_status')->default('pending');

            // Status
            $table->enum('status', ['pending', 'complete', 'return', 'rescheduling'])->default('pending');

            // File uploads
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('order_date');
            $table->index('wholesaler_id');
            $table->index('call_status');
            $table->index('product_code');
            $table->index('order_number');
        });

        Schema::table('wholesaler_orders', function (Blueprint $table) {
            $table->foreign('wholesaler_id')
                ->references('id')
                ->on('wholesalers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('wholesaler_products')
                ->onDelete('set null');
        });

        // Order items table
        Schema::create('wholesaler_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesaler_order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->index('wholesaler_order_id');
        });

        Schema::table('wholesaler_order_items', function (Blueprint $table) {
            if (Schema::hasTable('wholesaler_orders')) {
                $table->foreign('wholesaler_order_id')
                    ->references('id')
                    ->on('wholesaler_orders')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wholesaler_order_items');
        Schema::dropIfExists('wholesaler_orders');
    }
};
