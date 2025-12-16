<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained('wholesalers')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Pricing fields
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('packaging_cost', 15, 2)->default(0);

            // Inventory fields
            $table->integer('qty')->default(0);
            $table->integer('min_stock_level')->default(10);

            // Relations
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');

            // Other fields
            $table->text('features')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index(['wholesaler_id', 'status']);
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('qty');
            $table->index(['selling_price', 'discount']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_products');
    }
};
