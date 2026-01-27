<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if table exists before creating
        if (!Schema::hasTable('seller_product_price_histories')) {
            Schema::create('seller_product_price_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('seller_products')->onDelete('cascade');
                $table->string('seller_code');

                // Check if sellers table exists before adding foreign key
                if (Schema::hasTable('sellers') && Schema::hasColumn('sellers', 'code')) {
                    $table->foreign('seller_code')->references('code')->on('sellers')->onDelete('cascade');
                }

                // User who made the change
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

                // Price changes
                $table->json('old_prices')->nullable()->comment('JSON with old price data');
                $table->json('new_prices')->nullable()->comment('JSON with new price data');

                // Additional info
                $table->string('change_reason')->nullable()->comment('sale, season, competition, etc.');
                $table->text('notes')->nullable();

                // Metadata
                $table->timestamp('change_date');
                $table->string('change_type')->default('manual')->comment('manual, automatic, bulk_update');

                $table->timestamps();

                // Indexes
                $table->index('product_id');
                $table->index('seller_code');
                $table->index('updated_by');
                $table->index('change_date');
                $table->index(['product_id', 'change_date']);
            });
        } else {
            // Table already exists, check and add missing columns
            Schema::table('seller_product_price_histories', function (Blueprint $table) {
                // Add any missing columns here if needed
                // Example:
                // if (!Schema::hasColumn('seller_product_price_histories', 'new_column')) {
                //     $table->string('new_column')->nullable();
                // }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('seller_product_price_histories');
    }
};
