<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerProductImagesTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_product_images');
    }
}
