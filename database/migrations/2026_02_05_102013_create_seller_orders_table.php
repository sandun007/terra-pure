<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('seller_order_items');
        Schema::dropIfExists('seller_orders');

        Schema::create('seller_orders', function (Blueprint $table) {
            $table->id();

            // Order identifiers
            $table->string('order_id')->unique();
            $table->string('order_number')->unique();

            // Seller reference
            $table->unsignedBigInteger('seller_id');

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
            $table->index('seller_id');
            $table->index('call_status');
            $table->index('product_code');
            $table->index('order_id');
        });

        Schema::table('seller_orders', function (Blueprint $table) {
            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('seller_products')
                ->onDelete('set null');
        });

        // Order items table
        Schema::create('seller_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->index('seller_order_id');
        });

        Schema::table('seller_order_items', function (Blueprint $table) {
            if (Schema::hasTable('seller_orders')) {
                $table->foreign('seller_order_id')
                    ->references('id')
                    ->on('seller_orders')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_order_items');
        Schema::dropIfExists('seller_orders');
    }
};
