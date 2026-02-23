<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            // Add employee tracking fields
            if (!Schema::hasColumn('wholesaler_products', 'last_price_updated_by')) {
                $table->foreignId('last_price_updated_by')->nullable()->constrained('employees')->onDelete('set null')->after('status');
            }

            if (!Schema::hasColumn('wholesaler_products', 'last_price_updated_at')) {
                $table->timestamp('last_price_updated_at')->nullable()->after('last_price_updated_by');
            }

            // Add price history count
            if (!Schema::hasColumn('wholesaler_products', 'price_changes_count')) {
                $table->integer('price_changes_count')->default(0)->after('last_price_updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wholesaler_products', function (Blueprint $table) {
            $table->dropForeign(['last_price_updated_by']);
            $table->dropColumn(['last_price_updated_by', 'last_price_updated_at', 'price_changes_count']);
        });
    }
};
