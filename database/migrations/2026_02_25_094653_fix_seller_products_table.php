<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * FILE: database/migrations/2024_01_01_000001_fix_seller_products_table.php
 *
 * This migration fixes:
 *  1. `type` column — rebuilds the ENUM so MySQL stops truncating 'physical'/'digital'
 *  2. `seller_code` column — ensures it is VARCHAR(50) not an integer column
 *  3. `sku` column — adds it if missing
 *  4. Creates the full table from scratch if it does not yet exist
 *
 * Run with: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seller_products')) {
            // ── Full table creation (fresh install) ──────────────────────
            Schema::create('seller_products', function (Blueprint $table) {
                $table->id();

                // Foreign key columns
                $table->unsignedBigInteger('seller_id');
                $table->string('seller_code', 50)->nullable();          // VARCHAR, never int
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('brand_id')->nullable();

                // Identification
                $table->string('code', 50)->unique()->index()
                    ->comment('Auto-generated code: DX/ITM/0001');
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku')->nullable();
                $table->string('barcode')->nullable()->unique();

                // Content
                $table->text('description')->nullable();
                $table->text('features')->nullable();
                $table->string('image')->nullable();

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

                // Product meta — FIXED ENUM with both values explicitly defined
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
            // ── Fix existing table ────────────────────────────────────────

            // FIX 1: Rebuild the `type` column as VARCHAR first, then ENUM.
            //        Going directly from one ENUM definition to another can
            //        silently truncate data in some MySQL versions.
            DB::statement("ALTER TABLE `seller_products`
                           MODIFY `type` VARCHAR(20) NOT NULL DEFAULT 'physical'");

            DB::statement("ALTER TABLE `seller_products`
                           MODIFY `type` ENUM('physical','digital') NOT NULL DEFAULT 'physical'");

            // FIX 2: Ensure `seller_code` is VARCHAR(50), not an integer
            DB::statement("ALTER TABLE `seller_products`
                           MODIFY `seller_code` VARCHAR(50) NULL");

            // FIX 3: Add `sku` column if it is missing
            if (!Schema::hasColumn('seller_products', 'sku')) {
                Schema::table('seller_products', function (Blueprint $table) {
                    $table->string('sku')->nullable()->after('slug');
                });
            }

            // FIX 4: Add `features` as TEXT if it was created as VARCHAR
            //        (TEXT is needed for 2000-char content)
            DB::statement("ALTER TABLE `seller_products`
                           MODIFY `features` TEXT NULL");

            // FIX 5: Ensure `seller_code` index exists (optional but useful)
            // Wrapped in try/catch in case it already exists
            try {
                Schema::table('seller_products', function (Blueprint $table) {
                    $table->index('seller_code', 'seller_products_seller_code_index');
                });
            } catch (\Exception $e) {
                // Index already exists — safe to ignore
            }
        }
    }

    public function down(): void
    {
        // Revert type column back to plain VARCHAR (safe rollback)
        if (Schema::hasTable('seller_products')) {
            DB::statement("ALTER TABLE `seller_products`
                           MODIFY `type` VARCHAR(20) NOT NULL DEFAULT 'physical'");
        }
    }
};
