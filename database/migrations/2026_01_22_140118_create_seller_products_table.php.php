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
        // Drop table if exists to recreate with correct structure
        Schema::dropIfExists('seller_products');

        Schema::create('seller_products', function (Blueprint $table) {
            $table->id();

            // Seller reference
            $table->string('seller_code')->index();

            // Foreign key relations
            $table->foreignId('category_id')->nullable()->constrained('seller_categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('seller_brands')->onDelete('set null');

            // Product identification
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();

            // Pricing fields
            $table->decimal('stock_price', 15, 2)->default(0);
            $table->decimal('display_price', 15, 2)->default(0);
            $table->decimal('previous_display_price', 15, 2)->nullable();
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('profit_margin', 5, 2)->nullable();
            $table->decimal('packaging_cost', 15, 2)->default(0);

            // Inventory fields
            $table->integer('qty')->default(0);
            $table->integer('min_qty')->default(1);
            $table->integer('total_sold')->default(0);

            // Product details
            $table->text('features')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('img')->nullable();

            // Type and tracking
            $table->enum('type', ['stock_item', 'service', 'digital'])->default('stock_item');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->tinyInteger('cancel')->default(0);

            // User tracking
            $table->string('add_by')->default('seller');
            $table->string('update_by')->nullable();

            // Rating and reviews
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_ratings')->default(0);

            // Price change tracking
            $table->foreignId('last_price_updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('last_price_updated_at')->nullable();
            $table->integer('price_changes_count')->default(0);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Additional indexes for performance
            $table->index(['seller_code', 'status']);
            $table->index(['display_price', 'discount']);
            $table->index(['type', 'status']);
            $table->index('qty');
            $table->index('created_at');
        });

        // Add foreign key constraint for seller_code if sellers table exists
        if (Schema::hasTable('sellers') && Schema::hasColumn('sellers', 'code')) {
            Schema::table('seller_products', function (Blueprint $table) {
                $table->foreign('seller_code')
                    ->references('code')
                    ->on('sellers')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_products');
    }
};
