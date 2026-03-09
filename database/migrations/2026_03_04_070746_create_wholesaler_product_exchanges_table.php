<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists before creating
        if (!Schema::hasTable('wholesaler_product_exchanges')) {
            Schema::create('wholesaler_product_exchanges', function (Blueprint $table) {
                $table->id();

                // Source product (being exchanged FROM)
                $table->foreignId('source_product_id')
                    ->constrained('wholesaler_products')
                    ->onDelete('cascade');

                // Source wholesaler (original owner)
                $table->foreignId('source_wholesaler_id')
                    ->constrained('wholesalers')
                    ->onDelete('cascade');

                // Destination product (being exchanged TO)
                $table->foreignId('destination_product_id')
                    ->constrained('wholesaler_products')
                    ->onDelete('cascade');

                // Destination wholesaler (new owner)
                $table->foreignId('destination_wholesaler_id')
                    ->constrained('wholesalers')
                    ->onDelete('cascade');

                // Exchange details
                $table->string('exchange_type')->default('full');
                $table->decimal('exchange_quantity', 10, 2)->default(1);
                $table->decimal('source_price_at_time', 15, 2);
                $table->decimal('destination_price_at_time', 15, 2);
                $table->decimal('price_difference', 15, 2)->default(0);
                $table->decimal('adjustment_amount', 15, 2)->default(0);

                // Financial tracking
                $table->string('payment_method')->nullable();
                $table->string('payment_status')->default('pending');
                $table->timestamp('payment_date')->nullable();
                $table->string('transaction_reference')->nullable();

                // Employee who performed the exchange
                $table->foreignId('employee_id')
                    ->constrained('employees')
                    ->onDelete('cascade');

                // Status and tracking
                $table->string('status')->default('pending');
                $table->text('reason')->nullable();
                $table->text('notes')->nullable();
                $table->json('exchange_data')->nullable();

                // Approval tracking
                $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();

                // Timestamps
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index(['source_wholesaler_id', 'status']);
                $table->index(['destination_wholesaler_id', 'status']);
                $table->index(['employee_id', 'created_at']);
                $table->index('status');
                $table->index('created_at');
            });
        } else {
            // Table already exists, you might want to check if it needs updating
            $this->updateExistingTable();
        }
    }

    /**
     * Update existing table if needed
     */
    private function updateExistingTable()
    {
        Schema::table('wholesaler_product_exchanges', function (Blueprint $table) {
            // Add any missing columns here
            $columns = Schema::getColumnListing('wholesaler_product_exchanges');

            if (!in_array('deleted_at', $columns)) {
                $table->softDeletes();
            }

            if (!in_array('exchange_data', $columns)) {
                $table->json('exchange_data')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Be careful with down - only drop if you're sure
        // Schema::dropIfExists('wholesaler_product_exchanges');
    }
};
