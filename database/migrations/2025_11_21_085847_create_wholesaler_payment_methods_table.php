<?php


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
            $table->enum('method_type', ['bank', 'mobile']);
            $table->string('bank_name')->nullable();
            $table->string('account_name');
            $table->string('account_number');
            $table->string('branch')->nullable();
            $table->enum('mobile_provider', ['jazzcash', 'easypaisa', 'upaisa'])->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['wholesaler_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_payment_methods');
    }
}
