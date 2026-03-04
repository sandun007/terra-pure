<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix shipping_address: make it nullable with a default empty string
        if (Schema::hasColumn('wholesaler_orders', 'shipping_address')) {
            Schema::table('wholesaler_orders', function (Blueprint $table) {
                $table->text('shipping_address')->nullable()->default(null)->change();
            });
        }
    }

    public function down(): void
    {
        // No destructive rollback needed
    }
};
