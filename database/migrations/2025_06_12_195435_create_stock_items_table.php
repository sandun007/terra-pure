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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('stock_price');
            $table->string('discount_price');
            $table->string('selling_price');
            $table->string('qty');
            $table->string('low_qty');
            $table->date('stock_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
