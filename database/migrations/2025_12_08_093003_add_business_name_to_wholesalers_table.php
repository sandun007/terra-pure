<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wholesalers', function (Blueprint $table) {
            // Add the missing columns
            $table->string('business_name')->nullable()->after('password');
            $table->string('img')->nullable()->after('business_name');
        });
    }

    public function down(): void
    {
        Schema::table('wholesalers', function (Blueprint $table) {
            $table->dropColumn(['business_name', 'img']);
        });
    }
};
