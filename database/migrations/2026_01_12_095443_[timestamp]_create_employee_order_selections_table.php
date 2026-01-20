<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_order_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');
            $table->foreignId('order_id')
                ->constrained('wholesaler_orders')
                ->onDelete('cascade');
            $table->timestamp('selected_at')->useCurrent();
            $table->timestamps();

            // Ensure unique selection per employee-order pair
            $table->unique(['employee_id', 'order_id']);

            // Index for faster queries
            $table->index(['employee_id', 'selected_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_order_selections');
    }
};
