<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_print_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('wholesaler_orders')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('order_type')->default('wholesaler'); // wholesaler or seller
            $table->timestamp('printed_at')->useCurrent();
            $table->integer('print_count')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'employee_id']);
            $table->index('printed_at');
            $table->index('order_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_print_logs');
    }
};
