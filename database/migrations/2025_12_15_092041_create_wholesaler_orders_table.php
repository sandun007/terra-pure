<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained('wholesalers')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);

            // Customer information
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');

            // Payment information
            $table->enum('payment_method', ['cash_on_delivery', 'credit_card', 'bank_transfer']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            // Order status
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();
            // REMOVED: $table->softDeletes(); // Don't use soft deletes

            // Indexes
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        Schema::create('wholesaler_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('wholesaler_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_order_items');
        Schema::dropIfExists('wholesaler_orders');
    }
};
