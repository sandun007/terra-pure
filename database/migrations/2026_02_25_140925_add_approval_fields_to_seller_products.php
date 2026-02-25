<?php
// database/migrations/2026_02_01_000001_add_approval_fields_to_seller_products.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seller_products', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_products', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->timestamp('approved_at')->nullable()->after('approved_by');
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
                $table->text('rejection_reason')->nullable()->after('rejected_at');
                $table->text('approval_notes')->nullable()->after('rejection_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('seller_products', function (Blueprint $table) {
            $table->dropColumn([
                'approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at',
                'rejection_reason',
                'approval_notes'
            ]);
        });
    }
};
