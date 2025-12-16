<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_orders', function (Blueprint $table) {
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
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_orders');
    }
}
