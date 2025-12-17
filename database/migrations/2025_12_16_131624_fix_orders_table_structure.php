<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrdersTableStructure extends Migration
{
    public function up()
    {
        // Check if column exists before adding
        if (Schema::hasTable('orders')) {
            if (!Schema::hasColumn('orders', 'order_number')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->string('order_number')->unique()->after('id');
                });
            }
        }

        // Also create foreign key relationships if needed
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
            });
        }
    }

    public function down()
    {
        // Don't drop column to preserve data
    }
}
