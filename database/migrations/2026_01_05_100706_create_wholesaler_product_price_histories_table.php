<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop if exists (for fresh migration)
        Schema::dropIfExists('wholesaler_product_price_histories');

        Schema::create('wholesaler_product_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            // Old prices
            $table->decimal('old_cost_price', 15, 2);
            $table->decimal('old_selling_price', 15, 2);
            $table->decimal('old_discount', 5, 2)->default(0);
            $table->decimal('old_final_price', 15, 2);

            // New prices
            $table->decimal('new_cost_price', 15, 2);
            $table->decimal('new_selling_price', 15, 2);
            $table->decimal('new_discount', 5, 2)->default(0);
            $table->decimal('new_final_price', 15, 2);

            $table->text('notes')->nullable();
            $table->string('change_type', 20)->default('manual'); // manual, bulk
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['product_id', 'created_at']);
            $table->index('employee_id');
            $table->index('change_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_product_price_histories');
    }
};
