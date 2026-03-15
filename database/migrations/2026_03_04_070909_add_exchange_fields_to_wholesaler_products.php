<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Exchange tracking
            if (!Schema::hasColumn('wholesaler_products', 'exchange_count')) {
                $table->integer('exchange_count')->default(0)->after('price_changes_count');
            }

            if (!Schema::hasColumn('wholesaler_products', 'last_exchanged_at')) {
                $table->timestamp('last_exchanged_at')->nullable()->after('exchange_count');
            }

            if (!Schema::hasColumn('wholesaler_products', 'last_exchanged_by')) {
                $table->foreignId('last_exchanged_by')
                    ->nullable()
                    ->constrained('employees')
                    ->onDelete('set null')
                    ->after('last_exchanged_at');
            }

            if (!Schema::hasColumn('wholesaler_products', 'original_wholesaler_id')) {
                $table->foreignId('original_wholesaler_id')
                    ->nullable()
                    ->constrained('wholesalers')
                    ->onDelete('set null')
                    ->after('last_exchanged_by');
            }

            if (!Schema::hasColumn('wholesaler_products', 'exchange_history')) {
                $table->json('exchange_history')->nullable()->after('original_wholesaler_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $table->dropForeign(['last_exchanged_by']);
            $table->dropForeign(['original_wholesaler_id']);
            $table->dropColumn([
                'exchange_count',
                'last_exchanged_at',
                'last_exchanged_by',
                'original_wholesaler_id',
                'exchange_history'
            ]);
        });
    }
};
