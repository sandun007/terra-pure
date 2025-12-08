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
            $table->decimal('display_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2);
            $table->integer('qty')->default(0);
            $table->integer('min_order_quantity')->default(1);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->text('features')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['wholesaler_id', 'status']);
            $table->index('category_id');
            $table->index('brand_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_products');
    }
};
