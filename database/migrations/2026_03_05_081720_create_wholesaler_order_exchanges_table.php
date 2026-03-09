<?php
// database/migrations/2024_01_01_000001_create_wholesaler_order_exchanges_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerOrderExchangesTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_order_exchanges', function (Blueprint $table) {
            $table->id();

            // Source Order/Product Details
            $table->unsignedBigInteger('source_order_id');
            $table->unsignedBigInteger('source_order_item_id')->nullable();
            $table->unsignedBigInteger('source_product_id');
            $table->unsignedBigInteger('source_wholesaler_id');
            $table->string('source_product_name');
            $table->string('source_product_code');
            $table->integer('source_quantity');
            $table->decimal('source_price_per_unit', 10, 2);
            $table->decimal('source_total_price', 10, 2);

            // Destination Order/Product Details
            $table->unsignedBigInteger('destination_order_id')->nullable();
            $table->unsignedBigInteger('destination_order_item_id')->nullable();
            $table->unsignedBigInteger('destination_product_id');
            $table->unsignedBigInteger('destination_wholesaler_id');
            $table->string('destination_product_name');
            $table->string('destination_product_code');
            $table->integer('destination_quantity');
            $table->decimal('destination_price_per_unit', 10, 2);
            $table->decimal('destination_total_price', 10, 2);

            // Exchange Details
            $table->string('exchange_type')->default('full'); // full, partial, credit
            $table->integer('exchange_quantity');
            $table->decimal('price_difference', 10, 2)->default(0);
            $table->decimal('adjustment_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // cash, credit, bank_transfer
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_date')->nullable();

            // Exchange Identifier
            $table->string('exchange_number')->unique();

            // Status
            $table->string('status')->default('pending'); // pending, approved, completed, cancelled, rejected

            // Tracking
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('reason')->nullable();

            // JSON data for additional information
            $table->json('exchange_data')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('source_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('source_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('source_wholesaler_id')->references('id')->on('wholesalers')->onDelete('cascade');
            $table->foreign('destination_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('destination_wholesaler_id')->references('id')->on('wholesalers')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');

            // Indexes
            $table->index('exchange_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_order_exchanges');
    }
}
