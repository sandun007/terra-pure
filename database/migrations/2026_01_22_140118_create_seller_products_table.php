<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_products', function (Blueprint $table) {
            $table->id();

            // Seller relationship - VERY IMPORTANT: Always include seller_id
            $table->unsignedBigInteger('seller_id')->nullable()->index();
            $table->string('seller_code')->nullable()->index();

            // Category and Brand
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();

            // Product identification
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->nullable();

            // Description
            $table->text('description')->nullable();

            // Pricing - Use decimal for money
            $table->decimal('stock_price', 15, 2)->default(0);
            $table->decimal('display_price', 15, 2)->default(0);
            $table->decimal('previous_display_price', 15, 2)->nullable();
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('profit_margin', 5, 2)->nullable();
            $table->decimal('packaging_cost', 10, 2)->default(0);

            // Inventory
            $table->integer('qty')->default(0);
            $table->integer('min_qty')->default(1);
            $table->integer('total_sold')->default(0);

            // Additional details - TEXT instead of string for long content
            $table->text('features')->nullable(); // TEXT instead of string for long content// FIXED: Changed to text
            $table->string('barcode')->nullable()->unique();
            $table->string('image')->nullable(); // Using 'image' consistently

            // Product type and management - Use string, not enum (FIX for MariaDB)
            $table->string('type')->default('physical');
            $table->string('add_by')->nullable();
            $table->string('update_by')->nullable();
            $table->string('status')->default('active'); // String, not enum
            $table->boolean('cancel')->default(false);

            // Ratings
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_ratings')->default(0);

            // Price tracking
            $table->unsignedBigInteger('last_price_updated_by')->nullable();
            $table->timestamp('last_price_updated_at')->nullable();
            $table->integer('price_changes_count')->default(0);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['seller_id', 'status']);
            $table->index(['seller_id', 'category_id']);
            $table->index(['seller_id', 'brand_id']);
            $table->index(['seller_id', 'qty']);
            $table->index(['seller_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_products');
    }
};
