<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to ensure seller_products table has proper code column
 * Run this migration: php artisan migrate
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if seller_products table exists
        if (!Schema::hasTable('seller_products')) {
            // Create table if it doesn't exist
            Schema::create('seller_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->string('seller_code')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('brand_id')->nullable();

                // Product code in DX/ITM/XXXX format
                $table->string('code', 50)->unique()->index()->comment('Product code in format DX/ITM/0001');

                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku')->nullable();
                $table->text('description')->nullable();

                // Pricing
                $table->decimal('stock_price', 10, 2)->default(0);
                $table->decimal('display_price', 10, 2)->default(0);
                $table->decimal('previous_display_price', 10, 2)->nullable();
                $table->decimal('discount', 5, 2)->default(0);
                $table->decimal('profit_margin', 10, 2)->nullable();
                $table->decimal('packaging_cost', 10, 2)->default(0);

                // Inventory
                $table->integer('qty')->default(0);
                $table->integer('min_qty')->default(1);
                $table->integer('total_sold')->default(0);

                // Product details
                $table->text('features')->nullable();
                $table->string('barcode')->nullable()->unique();
                $table->string('image')->nullable();
                $table->enum('type', ['physical', 'digital'])->default('physical');

                // Management
                $table->string('add_by')->default('seller');
                $table->string('update_by')->nullable();
                $table->string('status')->default('active');
                $table->boolean('cancel')->default(false);

                // Ratings
                $table->decimal('average_rating', 3, 2)->default(0);
                $table->integer('total_reviews')->default(0);
                $table->integer('total_ratings')->default(0);

                // Price tracking
                $table->string('last_price_updated_by')->nullable();
                $table->timestamp('last_price_updated_at')->nullable();
                $table->integer('price_changes_count')->default(0);

                $table->timestamps();
                $table->softDeletes();

                // Foreign keys
                $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('seller_categories')->onDelete('set null');
                $table->foreign('brand_id')->references('id')->on('seller_brands')->onDelete('set null');

                // Indexes
                $table->index('seller_id');
                $table->index('status');
                $table->index(['seller_id', 'status']);
            });
        } else {
            // Update existing table to ensure code column is correct
            Schema::table('seller_products', function (Blueprint $table) {
                // Check if code column exists and update it
                if (!Schema::hasColumn('seller_products', 'code')) {
                    $table->string('code', 50)->unique()->index()
                        ->after('brand_id')
                        ->comment('Product code in format DX/ITM/0001');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in down, just remove the comment
        if (Schema::hasTable('seller_products')) {
            Schema::table('seller_products', function (Blueprint $table) {
                // Remove comment if needed
            });
        }
    }
};
