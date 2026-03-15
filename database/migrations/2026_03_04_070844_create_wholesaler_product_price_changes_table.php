<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wholesaler_product_price_changes', function (Blueprint $table) {
            $table->id();

            // Product reference
            $table->foreignId('product_id')
                ->constrained('wholesaler_products')
                ->onDelete('cascade');

            // Wholesaler reference (owner at time of change)
            $table->foreignId('wholesaler_id')
                ->constrained('wholesalers')
                ->onDelete('cascade');

            // Employee who made the change
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            // Old prices
            $table->decimal('old_cost_price', 15, 2)->nullable();
            $table->decimal('old_selling_price', 15, 2);
            $table->decimal('old_display_price', 15, 2);
            $table->decimal('old_discount', 5, 2)->default(0);
            $table->decimal('old_final_price', 15, 2);

            // New prices
            $table->decimal('new_cost_price', 15, 2)->nullable();
            $table->decimal('new_selling_price', 15, 2);
            $table->decimal('new_display_price', 15, 2);
            $table->decimal('new_discount', 5, 2)->default(0);
            $table->decimal('new_final_price', 15, 2);

            // Change calculations
            $table->decimal('price_difference', 15, 2);
            $table->decimal('percentage_change', 8, 2);

            // Change metadata
            $table->string('change_type')->default('manual'); // manual, bulk, formula
            $table->string('change_reason')->nullable();
            $table->text('notes')->nullable();
            $table->json('change_data')->nullable(); // Additional metadata

            // Approval workflow (if needed)
            $table->string('status')->default('approved'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'created_at']);
            $table->index(['wholesaler_id', 'created_at']);
            $table->index(['employee_id', 'created_at']);
            $table->index('status');
            $table->index('change_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wholesaler_product_price_changes');
    }
};
