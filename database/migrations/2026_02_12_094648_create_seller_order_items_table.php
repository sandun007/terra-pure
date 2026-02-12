<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists first
        Schema::dropIfExists('seller_order_items');

        // Create the table first without foreign key
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

            // Add index
            $table->index('seller_order_id');
        });

        // Add foreign key constraint separately
        Schema::table('seller_order_items', function (Blueprint $table) {
            // Ensure the parent table exists
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
    }
};
