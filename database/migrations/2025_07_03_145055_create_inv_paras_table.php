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
        Schema::create('inv_paras', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('address');
            $table->string('phone_no_01');
            $table->string('phone_no_02');
            $table->string('email');
            $table->string('logo');
            $table->string('category_code');
            $table->string('brand_code');
            $table->string('product_code');
            $table->string('seller_code');
            $table->string('supplier_code');
            $table->string('order_code');
            $table->string('stock_code');
            $table->string('grn_code');
            $table->string('inv_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_paras');
    }
};
