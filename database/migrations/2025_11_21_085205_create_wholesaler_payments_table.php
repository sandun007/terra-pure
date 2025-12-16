<?php
// database/migrations/xxxx_xx_xx_create_wholesaler_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWholesalerPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('wholesaler_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']); // credit = income, debit = withdrawal
            $table->enum('payment_type', ['order_payment', 'withdrawal', 'refund', 'bonus']);
            $table->string('payment_method')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['wholesaler_id', 'status']);
            $table->index(['wholesaler_id', 'type']);
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesaler_payments');
    }
}
