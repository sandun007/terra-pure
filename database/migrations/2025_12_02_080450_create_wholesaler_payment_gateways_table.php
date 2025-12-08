<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerPaymentGatewaysTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
            $table->string('gateway_name');
            $table->enum('gateway_type', ['stripe', 'paypal', 'jazzcash', 'easypaisa', 'upaisa', 'stripe_checkout']);
            $table->text('api_key'); // Encrypted
            $table->text('api_secret'); // Encrypted
            $table->string('api_url');
            $table->string('webhook_url')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['wholesaler_id', 'status']);
            $table->index(['gateway_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_payment_gateways');
    }
}
