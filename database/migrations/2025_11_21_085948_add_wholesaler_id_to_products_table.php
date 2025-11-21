<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'wholesaler_id')) {
                $table->foreignId('wholesaler_id')->nullable()->constrained('wholesalers')->onDelete('cascade');
            }

            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 15, 2)->nullable();
            }

            if (!Schema::hasColumn('products', 'min_order_quantity')) {
                $table->integer('min_order_quantity')->default(1);
            }

            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'wholesaler_id')) {
                $table->dropForeign(['wholesaler_id']);
                $table->dropColumn('wholesaler_id');
            }

            $columnsToCheck = ['wholesale_price', 'min_order_quantity', 'status'];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
