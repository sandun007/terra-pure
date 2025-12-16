<?php
// database/migrations/xxxx_xx_xx_create_wholesaler_payment_methods_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerPaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
            $table->enum('method_type', ['bank', 'mobile_money', 'card']);
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('mobile_provider')->nullable(); // mpesa, tigo_pesa, etc
            $table->string('card_last_four')->nullable();
            $table->string('card_type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['wholesaler_id', 'is_default']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_payment_methods');
    }
}
