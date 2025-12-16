<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('wholesaler_orders')->onDelete('cascade');
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

    public function down()
    {
        Schema::dropIfExists('wholesaler_order_items');
    }
}
