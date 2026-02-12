<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop if exists first
        Schema::dropIfExists('seller_orders');

        Schema::create('seller_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->date('rescheduling_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('platform')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('district')->nullable();
            $table->text('remark')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('seller_id');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->timestamps();

            // Add indexes first
            $table->index('status');
            $table->index('order_date');
            $table->index('seller_id');
        });

        // Add foreign key separately
        Schema::table('seller_orders', function (Blueprint $table) {
            $table->foreign('seller_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_orders');
    }
};
