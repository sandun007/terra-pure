<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->foreignId('product_id')->constrained('wholesaler_products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2);
            $table->timestamps();

            $table->index(['user_id', 'session_id']);
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
