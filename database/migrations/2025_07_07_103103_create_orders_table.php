<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->date('date');
            $table->date('delivery_date')->nullable();
            $table->string('seller_no');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('total')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone_01');
            $table->string('customer_phone_02');
            $table->string('customer_address');
            $table->string('city');
            $table->string('remark')->nullable();
            $table->date('status_date')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
