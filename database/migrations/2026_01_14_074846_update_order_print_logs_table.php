<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists
        if (Schema::hasTable('order_print_logs')) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('order_print_logs', 'notes')) {
                Schema::table('order_print_logs', function (Blueprint $table) {
                    $table->text('notes')->nullable()->after('print_count');
                });
            }

            // Check and add foreign key constraints if needed
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $table = $sm->listTableDetails('order_print_logs');

            if (!$table->hasForeignKey('order_print_logs_order_id_foreign')) {
                Schema::table('order_print_logs', function (Blueprint $table) {
                    $table->foreign('order_id')->references('id')->on('wholesaler_orders')->onDelete('cascade');
                });
            }

            if (!$table->hasForeignKey('order_print_logs_employee_id_foreign')) {
                Schema::table('order_print_logs', function (Blueprint $table) {
                    $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                });
            }
        } else {
            // Create table if it doesn't exist
            Schema::create('order_print_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('wholesaler_orders')->onDelete('cascade');
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('order_type')->default('wholesaler');
                $table->timestamp('printed_at')->useCurrent();
                $table->integer('print_count')->default(1);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'employee_id']);
                $table->index('printed_at');
                $table->index('order_type');
            });
        }
    }

    public function down(): void
    {
        // Don't drop the table in down method to avoid data loss
        Schema::table('order_print_logs', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['order_id']);
            $table->dropForeign(['employee_id']);

            // Drop indexes
            $table->dropIndex(['order_id', 'employee_id']);
            $table->dropIndex(['printed_at']);
            $table->dropIndex(['order_type']);

            // Drop column
            if (Schema::hasColumn('order_print_logs', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
