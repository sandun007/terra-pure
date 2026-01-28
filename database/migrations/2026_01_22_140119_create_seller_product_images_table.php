<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('seller_products')->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_type')->default('product')->comment('product, thumbnail, gallery, etc.');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->string('alt_text')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index(['product_id', 'is_primary']);
            $table->index('sort_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_product_images');
    }
};
